using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;

namespace Kalapacsvetes
{
    class Program
    {
        static List<Sportolo> adatok = new List<Sportolo>();
        static void Main(string[] args)
        {
            adatbeolvasas();
            feladat05();
            feladat06();
            feladat07();
            feladat08();
            Console.ReadLine();
        }

        private static void feladat08()
        {
            StreamWriter irocsatorna = new StreamWriter("magyarok.txt");
            irocsatorna.WriteLine("Helyezés;Eredmény;Sportoló;Országkód;Helyszín;Dátum");
            foreach (Sportolo adat in adatok)
            {
                if (adat.orszagKod == "HUN")
                {
                    irocsatorna.WriteLine("{0};{1};{2};{3};{4};{5}", adat.helyezes, adat.eredmeny, adat.sportolo, adat.orszagKod,adat.helyszin, adat.datum);
                }
                
            }

            irocsatorna.Close();
        }

        private static void feladat07()
        {
            Console.WriteLine("7. feladat: Statisztika");

            var statisztika =
                from adat in adatok
                group adat by adat.orszagKod into orszag
                //orderby orszag.Key  //beturendbe allitas az orszagok kozott
                select new
                {
                    Orszag = orszag.Key,
                    Db = orszag.Count()
                };

            foreach (var elem in statisztika)
            {
                Console.WriteLine($"\t{elem.Orszag} - {elem.Db} dobás");
            }
        }

        private static void feladat06()
        {
            Console.WriteLine("6. feladat: Adjon meg egy évszámot: ");
            string bekert_evszam = Console.ReadLine();
            int legjobb_dobasok_szama = 0;

            foreach (Sportolo adat in adatok)
            {
                if (adat.evszam == bekert_evszam)
                {
                    legjobb_dobasok_szama++;
                }

            }

            if (legjobb_dobasok_szama == 0)
            {
                Console.WriteLine("\tEgy dobás sem került be ebben az évben.");
            }
            else
            {
                Console.WriteLine("\t{0} darab dobás került be ebben az évben.", legjobb_dobasok_szama);
            }
            

            foreach (Sportolo adat in adatok)
            {
                if (adat.evszam == bekert_evszam)
                {
                    Console.WriteLine("\t"+adat.sportolo);
                }
            }
        }

        private static void feladat05()
        {
            double magyarok_szama = 0;  //tipus double hogy lehessen atlagot szamolni
            double ossz_eredmeny = 0;

            foreach (Sportolo adat in adatok)
            {
                if (adat.orszagKod == "HUN")
                {
                    magyarok_szama++;
                    ossz_eredmeny = ossz_eredmeny + double.Parse(adat.eredmeny);
                }
            }

            /*
            double magyarAtlag =
                (from adat in adatok
                 where adat.orszagKod == "HUN"
                 select adat.eredmeny).Average()
                 ;
            */
             
            Console.WriteLine("5. feladat: A magyar sportolók átlagosan {0} métert dobtak.", ossz_eredmeny / magyarok_szama);
        }

        private static void adatbeolvasas()
        {
            StreamReader olvasocsatorna = new StreamReader("kalapacsvetes.txt");

            string elsosor = olvasocsatorna.ReadLine(); // fejlec kihagyasa
            string sor;

            while (!olvasocsatorna.EndOfStream)
            {
                sor = olvasocsatorna.ReadLine();
                Sportolo adat = new Sportolo(sor);
                adatok.Add(adat);
            }

            olvasocsatorna.Close();

            Console.WriteLine("4. feladat: {0} dobás eredménye található.", adatok.Count);
        }
    }
}
