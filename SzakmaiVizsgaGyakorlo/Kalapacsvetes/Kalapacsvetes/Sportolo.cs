using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Kalapacsvetes
{
    class Sportolo
    {
        public string helyezes; //int
        public string eredmeny; //itt valszeg nem lesz jo a string mert ket tizedesjegy kell
        public string sportolo;
        public string orszagKod;
        public string helyszin;
        public string datum;  //DateTime
        public string evszam;

        private int myVar;

        public int MyProperty
        {
            get { return myVar; }
            set { myVar = value; }
        }

        
        //konstruktor
        public Sportolo(string sor)
        {
            string[] darabol;
            darabol = sor.Split(';');

            this.helyezes = darabol[0];
            this.eredmeny = darabol[1];
            this.sportolo = darabol[2];
            this.orszagKod = darabol[3];
            this.helyszin = darabol[4];
            this.datum = darabol[5];

            string[] datum_darabolva;
            datum_darabolva = datum.Split('.');
            this.evszam = datum_darabolva[0];
        } 
        
        

    }
}
