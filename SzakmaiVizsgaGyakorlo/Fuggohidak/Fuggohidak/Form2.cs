using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Fuggohidak
{
    public partial class Form2 : Form
    {
        List<Fuggohid> adatok = new List<Fuggohid>();
        public Form2(List<Fuggohid> lista)
        {
            InitializeComponent();
            for (int i = 0; i < lista.Count; i++)
            {
                adatok.Add(lista[i]);
            }
        }

        private void Form2_Load(object sender, EventArgs e)
        {
            for (int i = 0; i < adatok.Count; i++)
            {
                bool van = false;

                for (int j = 0; j < keresesComboBox.Items.Count; j++)
                {
                    if (keresesComboBox.Items[j].ToString() == adatok[i].orszag)
                    {
                        van = true;
                    }
                }

                if (!van)
                {
                    keresesComboBox.Items.Add(adatok[i].orszag);
                }
            }   
        }

        private void bezarasBtn_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void keresesBtn_Click(object sender, EventArgs e)
        {
            keresesLB.Items.Clear();

            string valasztottOrszag = keresesComboBox.Text;

            for (int i = 0; i < adatok.Count; i++)
            {
                if (adatok[i].orszag == valasztottOrszag)
                {
                    if (!keresesCheckBox.Checked)
                    {
                        keresesLB.Items.Add(adatok[i].nev);
                    }
                    else if (adatok[i].hossz < 1000)
                    {
                        keresesLB.Items.Add(adatok[i].nev);
                    }
          
                }
            }
        }

        private void keresesLB_SelectedIndexChanged(object sender, EventArgs e)
        {
            
        }

        private void keresesComboBox_SelectedIndexChanged(object sender, EventArgs e)
        {
            keresesBtn_Click(null, null);
        }

        private void keresesCheckBox_CheckedChanged(object sender, EventArgs e)
        {
            keresesBtn_Click(null, null);
        }
    }
}
