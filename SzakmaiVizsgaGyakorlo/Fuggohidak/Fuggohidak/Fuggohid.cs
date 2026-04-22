using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Fuggohidak
{
    public class Fuggohid
    {
        public int helyezes;
        public string nev;
        public string hely;
        public string orszag;
        public int hossz;
        public int atadas_eve;

        public Fuggohid(string sor)
        {
            string[] darabol;
            darabol = sor.Split('\t');

            this.helyezes = int.Parse(darabol[0]);
            this.nev = darabol[1];
            this.hely = darabol[2];
            this.orszag = darabol[3];
            this.hossz = int.Parse(darabol[4]);
            this.atadas_eve = int.Parse(darabol[5]);
        }
    }

    

}
