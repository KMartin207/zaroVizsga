from machine import Pin, PWM, SPI
from mfrc522 import MFRC522
import time
import ST7735
from picozero import LED
import network
import urequests
import json

# --- WiFi beállítások ---
WIFI_SSID = "iPhone"
WIFI_PASSWORD = "wifi1234"
SERVER_URL = "http://proparking.hu/adatkezeles.php"

# --- Gombok ---
button1 = Pin(12, Pin.IN, Pin.PULL_UP)  # 1. gomb
button2 = Pin(14, Pin.IN, Pin.PULL_UP)  # 2. gomb  
button3 = Pin(8, Pin.IN, Pin.PULL_UP)  # 3. gomb
button4 = Pin(9, Pin.IN, Pin.PULL_UP)  # Törlés gomb

# --- Változók ---
current_code = ""
current_mode = "card"  # card vagy code
last_mode_button_state = 1
last_card_usage = {}  # Kártya ID -> utolsó használat időpontja
card_cooldown = 60    # 60 másodperc cooldown kártyánként

# --- WiFi csatlakozás ---
def connect_wifi():
    wlan = network.WLAN(network.STA_IF)
    wlan.active(True)
    
    if not wlan.isconnected():
        print('WiFi csatlakozás...')
        wlan.connect(WIFI_SSID, WIFI_PASSWORD)
        
        timeout = 10
        while not wlan.isconnected() and timeout > 0:
            time.sleep(1)
            timeout -= 1
            print('Várakozás...')
    
    if wlan.isconnected():
        print('WiFi csatlakozva!')
        print('IP cím:', wlan.ifconfig()[0])
        return True
    else:
        print('Nem sikerült csatlakozni a Wi-Fi-hez!')
        return False

# --- Kártya ellenőrzése a szerveren ---
def check_card_on_server(card_id):
    try:
        data = {
            "type": "card",
            "card_id": card_id
        }
        
        json_data = json.dumps(data)
        
        headers = {'Content-Type': 'application/json'}
        response = urequests.post(SERVER_URL, data=json_data, headers=headers)
        
        if response.status_code == 200:
            result = response.json()
            return result
        else:
            print("Hiba a szerver kommunikációban. Status code:", response.status_code)
            return {"success": False, "message": "Szerver hiba"}
            
    except Exception as e:
        print("Hiba a szerver kommunikációban:", e)
        return {"success": False, "message": "Kommunikációs hiba"}
    finally:
        try:
            response.close()
        except:
            pass

# --- Kód ellenőrzése a szerveren ---
def check_code_on_server(code):
    try:
        data = {
            "type": "code", 
            "code": code
        }
        
        json_data = json.dumps(data)
        
        headers = {'Content-Type': 'application/json'}
        response = urequests.post(SERVER_URL, data=json_data, headers=headers)
        
        if response.status_code == 200:
            result = response.json()
            return result
        else:
            print("Hiba a szerver kommunikációban. Status code:", response.status_code)
            return {"success": False, "message": "Szerver hiba"}
            
    except Exception as e:
        print("Hiba a szerver kommunikációban:", e)
        return {"success": False, "message": "Kommunikációs hiba"}
    finally:
        try:
            response.close()
        except:
            pass

# --- NFC olvasó ---
reader = MFRC522(spi_id=0, sck=2, miso=4, mosi=3, cs=1, rst=7)

# --- Kijelző ---
spi = SPI(1, baudrate=8000000, polarity=0, phase=0)
d = ST7735.ST7735(spi, rst=6, ce=5, dc=16)

# --- Szervo ---
servo = PWM(Pin(0))  
servo.freq(50)

# --- LED-ek ---
NFCred = Pin(18, Pin.OUT)
NFCgreen = Pin(19, Pin.OUT)
blue = Pin(20, Pin.OUT)
SorompoRed = LED(15)
SorompoGreen = LED(13)

# --- Kijelző alap beállítása ---
d.reset()
d.begin()
d._bground = 0xffff
d.fill_screen(d._bground)

# --- Szervó vezérlés ---
def set_angle(angle):
    min_us = 500
    max_us = 2500
    us = min_us + (angle / 180) * (max_us - min_us)
    duty_u16 = int(us * 65535 * 50 / 1_000_000)
    servo.duty_u16(duty_u16)

def move_slow(start, end, step=1, delay=0.02):
    if start < end:
        rng = range(start, end+1, step)
    else:
        rng = range(start, end-1, -step)
    for angle in rng:
        set_angle(angle)
        time.sleep(delay)

# --- Kijelző üzenet ---
def display_message(line1, line2="", line3="", line4=""):
    d.reset()
    d.begin()
    d._color = 0
    d.set_rotation(1)
    d.fill_screen(d._bground)
    
    d.p_string(1, 10, line1)
    if line2:
        d.p_string(1, 30, line2)
    if line3:
        d.p_string(1, 50, line3)
    if line4:
        d.p_string(1, 70, line4)

# --- Kártya cooldown ellenőrzése ---
def check_card_cooldown(card_id):
    global last_card_usage
    
    current_time = time.time()
    
    if card_id in last_card_usage:
        time_since_last_use = current_time - last_card_usage[card_id]
        if time_since_last_use < card_cooldown:
            remaining = int(card_cooldown - time_since_last_use)
            return False, remaining
    
    # Nincs cooldown vagy lejárt
    last_card_usage[card_id] = current_time
    return True, 0

# --- Kód bevitel kezelése ---
def handle_button_press(button_num):
    global current_code
    
    if button_num == 4:  # Törlés gomb
        if len(current_code) > 0:
            current_code = current_code[:-1]
            update_display()
        return
    
    # Szám gomb (1-3)
    if len(current_code) < 4:
        current_code += str(button_num)
        update_display()
        
        # Ha elértük a 4 karaktert, automatikusan elküldjük
        if len(current_code) == 4:
            check_and_send_code()

def update_display():
    if current_mode == "card":
        display_message("Kerem a kartyat", "", "Nyomj meg egy gombot", "a kod bevitelhez")
    else:
        # Kód mód - csak a kód jelenik meg
        display_message("Kod bevitel:", current_code + "_" * (4 - len(current_code)), "1-3: Szamok", "4: Torles")

def check_and_send_code():
    global current_code, current_mode
    
    display_message("Kod ellenorzes...", current_code)
    
    result = check_code_on_server(current_code)
    
    if result.get("success"):
        user_name = result.get("user_name", "")
        action_type = result.get("action_type", "enter")
        
        if action_type == "enter":
            display_message("Kod elfogadva!", user_name, "Sorompo nyit...")
        else:
            display_message("Viszlat!", user_name, "Sorompo nyit...")
            
        NFCred.value(0)
        NFCgreen.value(1)
        blue.value(0)
        open_gate()
    else:
        display_message("Kod elutasitva!", result.get("message", ""))
        NFCred.value(1)
        NFCgreen.value(0)
        blue.value(0)
        time.sleep(3)
    
    # Visszaállítás kártya módba
    current_mode = "card"
    current_code = ""
    update_display()

# --- NFC ellenőrzés ---
def NFCcheckSet(result, card):
    if result.get("success"):
        NFCred.value(0)
        NFCgreen.value(1)
        blue.value(0)

        user_name = result.get("user_name", "")
        action_type = result.get("action_type", "enter")
        
        if action_type == "enter":
            display_message("Udvozoljuk!", user_name, "Sorompo nyit...")
        else:
            display_message("Viszlat!", user_name, "Sorompo nyit...")
        
        return True
    else:
        NFCred.value(1)
        NFCgreen.value(0)
        blue.value(0)

        display_message("Kartya megtagadva", result.get("message", ""))
        time.sleep(2)
        return False

# --- Sorompó nyitás ---
def open_gate():
    print("Sorompó lassan fel...")
    move_slow(90, 0, step=1, delay=0.05)  # 90°-tól 0°-ig (felfelé nyitás)

    SorompoRed.off()
    SorompoGreen.on()

    time.sleep(5)

    SorompoRed.on()
    SorompoGreen.off()

    print("Sorompó lassan le...")
    move_slow(0, 90, step=1, delay=0.05)  # 0°-tól 90°-ig (lefelé zárás)
    
    return True

# --- Gomb kezelés ---
def check_buttons():
    global current_mode, current_code, last_mode_button_state
    
    # Mód váltó gomb ellenőrzése (bármelyik gomb) - CSAK KÁRTYA MÓDBAN
    current_mode_button_state = (not button1.value() or not button2.value() or 
                                not button3.value() or not button4.value())
    
    # Ha mód váltás történt (bármelyik gomb lenyomva) és kártya módban vagyunk
    if current_mode_button_state and not last_mode_button_state and current_mode == "card":
        # Kártya módból kód módba váltás
        current_mode = "code"
        current_code = ""
        update_display()
        print("Atvaltva kod modba")
    
    last_mode_button_state = current_mode_button_state
    
    # Ha kód módban vagyunk, kezeljük a gombokat
    if current_mode == "code":
        if not button1.value():  # Gomb 1 lenyomva
            handle_button_press(1)
            time.sleep(0.3)  # Debounce
        elif not button2.value():  # Gomb 2 lenyomva
            handle_button_press(2)
            time.sleep(0.3)
        elif not button3.value():  # Gomb 3 lenyomva
            handle_button_press(3)
            time.sleep(0.3)
        elif not button4.value():  # Törlés gomb lenyomva
            handle_button_press(4)
            time.sleep(0.3)

# --- NFC olvasás független módtól ---
def check_nfc():
    reader.init()
    (stat, tag_type) = reader.request(reader.REQIDL)
    if stat == reader.OK:
        (stat, uid) = reader.SelectTagSN()
        if stat == reader.OK:
            card = int.from_bytes(bytes(uid), "little", False)
            print("=" * 50)
            print("BEOLVASOTT KÁRTYA ID:", card)
            print("=" * 50)
            
            # Kártya cooldown ellenőrzése
            
            
            # Kártya ellenőrzése a szerveren
            server_result = check_card_on_server(card)
            
            if server_result.get("success"):
                if NFCcheckSet(server_result, card):
                    open_gate()
                    update_display()
            else:
                NFCcheckSet(server_result, card)
                # Visszaállítjuk a kijelzőt a módnak megfelelően
                update_display()
            
            return True
    return False

# --- Fő program ---
print("WiFi csatlakozás indítása...")
connect_wifi()

# Kezdeti kijelző
update_display()

# --- Végtelen parkolórendszer ciklus ---
while True:
    NFCred.value(0)
    NFCgreen.value(0)
    blue.value(1)
    SorompoRed.on()
    
    # Gombok ellenőrzése
    check_buttons()
    
    # NFC olvasás - FÜGGETLEN a módtól, mindig működik
    # De csak akkor olvasunk, ha nincs éppen kód bevitel folyamatban
    if current_mode == "card" or (current_mode == "code" and current_code == ""):
        if check_nfc():
            # Ha kártya módban voltunk és sikerült a kártya olvasás,
            # akkor visszaállítjuk a kijelzőt
            if current_mode == "code":
                current_mode = "card"
                current_code = ""
                update_display()
    
    time.sleep(0.05)




