using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;

namespace Fuggohidak
{
    public partial class Form1 : Form
    {
        static List<Fuggohid> adatok = new List<Fuggohid>();
        public Form1()
        {
            InitializeComponent();
        }
        
        private void Form1_Shown(object sender, EventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            try
            {
                
                if (openOFD.ShowDialog() == DialogResult.OK)
                {
                    StreamReader olvasocsaorna = new StreamReader("fuggohidak.csv");
                    string elsosor = olvasocsaorna.ReadLine();
                    string sor;

                    while (!olvasocsaorna.EndOfStream)
                    {
                        sor = olvasocsaorna.ReadLine();
                        Fuggohid adat = new Fuggohid(sor);
                        adatok.Add(adat);
                        hidakLB.Items.Add(adat.nev);
                    }
                    olvasocsaorna.Close();
                }
            }
            catch (Exception kivetel)
            {
                MessageBox.Show("Hiba történt! \n" + kivetel.ToString(), "Hibaüzenet");
            }

            hidakLB.SelectedIndex = 0;
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            
        }

        private void kilépésToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void kilepesBtn_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void hidakLB_SelectedIndexChanged(object sender, EventArgs e)
        {
            var h = adatok[hidakLB.SelectedIndex];

            helyTBx.Text = h.hely;
            orszagTBx.Text = h.orszag;
            hosszTBx.Text = h.hossz.ToString();
            evTBx.Text = h.atadas_eve.ToString();
        }

        private void ketezer_elottRBtn_CheckedChanged(object sender, EventArgs e)
        {
            if (ketezer_elottRBtn.Checked)
            {
                int db = adatok.Count(x => x.atadas_eve < 2000);
                darabTBx.Text = db.ToString();
            }
        }

        private void ketezer_utanRBtn_CheckedChanged(object sender, EventArgs e)
        {
            if (ketezer_utanRBtn.Checked)
            {
                int db = adatok.Count(x => x.atadas_eve > 2000);
                darabTBx.Text = db.ToString();
            }
        }

        private void keresésToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Form2 form = new Form2(adatok);
            this.Hide();
            form.ShowDialog();
            this.Show();
        }
    }
}
