using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;

namespace sudokuCLI
{
    class Feladvany
    {
        public string Kezdo { get; private set; }
        public int Meret { get; private set; }

        public Feladvany(string sor)
        {
            Kezdo = sor;
            Meret = Convert.ToInt32(Math.Sqrt(sor.Length));
        }

        public void Kirajzol()
        {
            for (int i = 0; i < Kezdo.Length; i++)
            {
                if (Kezdo[i] == '0')
                {
                    Console.Write(".");
                }
                else
                {
                    Console.Write(Kezdo[i]);
                }
                if (i % Meret == Meret - 1)
                {
                    Console.WriteLine();
                }
            }
        }
    }




    internal class Program
    {
        
        struct Mezok
        {
            public string mezo;
        }
        

        static List<Mezok> adatok = new List<Mezok>();


        static void feladat03_04()
        {
            

            StreamReader olvasocsatorna = new StreamReader("feladvanyok.txt");


            while (!olvasocsatorna.EndOfStream)
            {
                string sor = olvasocsatorna.ReadLine();
                string[] darabol = sor.Split('\n');

                Mezok m = new Mezok();

                m.mezo = darabol[0];
                adatok.Add(m);
            }


            olvasocsatorna.Close();


            Console.WriteLine("3. feladat: Beolvasva {0} feladvány", adatok.Count);



            int bekert;
            do
            {
                Console.Write("4. feladat: Kérem a feladvány méretét [4..9]: ");
                bekert = int.Parse(Console.ReadLine());

            } while (bekert < 4 || bekert > 9);


            int egyezo_hossz = 0;

            for (int i = 0; i < adatok.Count; i++)
            {
                if (adatok[i].mezo.Length == bekert * bekert)
                {
                    egyezo_hossz++;
                }
            }

            
            Console.WriteLine("{0} X {1} méretű feladványból {2} darab van tárolva", bekert, bekert, egyezo_hossz);


        }

        static void feladat05()
        {
            Console.WriteLine("5. feladat: A kiválasztott feladvány:");
            Console.WriteLine(adatok[0].mezo);
        }

        static void feladat06()
        {
            int nullak_szama = 0;


            int vizsgalt_sor_hossza = adatok[0].mezo.Length;
            string vizsgalt_sor = adatok[0].mezo;

            for (int i = 0; i < vizsgalt_sor_hossza; i++)
            {
                if (vizsgalt_sor[i] == 0)
                {
                    nullak_szama++;
                }
            }
            
            
            


            

            

            //Console.WriteLine("6. feladat: A feladvány kitöltöttsége: %");
        }

        static void Main(string[] args)
        {
            feladat03_04();
            feladat05();
            feladat06();
            Console.Write("A kilépéshez üss entert. ");
            Console.ReadLine();
        }
    }
}
