using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace GyakorloBevezetoFeladatok
{
    

    public partial class Form1 : Form
    {
        private Point ugrasBtnOriginalLocation;

        public Form1()
        {
            InitializeComponent();
            ugrasBtnOriginalLocation = ugrasBtn.Location;
        }

        private void Form1_MouseEnter(object sender, EventArgs e)
        {

        }

        private void button1_MouseEnter(object sender, EventArgs e)
        {
            eltunes_btn.Visible = false;
        }

        private void button1_MouseLeave(object sender, EventArgs e)
        {
            eltunes_btn.Visible = true;
        }

        private void kilepesBtn_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        
        private void eltunes_btn_Click(object sender, EventArgs e)
        {

        }
        
        
        private void lathatatlanBTN_Click(object sender, EventArgs e)
        {
            

            if (inaktivBtn.Visible == true)
            {
                inaktivBtn.Visible = false;
            }
            else
            {
                inaktivBtn.Visible = true;
            }

        }

        private void inaktivBtn_Click(object sender, EventArgs e)
        {
            if(lathatatlanBTN.Enabled == true)
            {
                lathatatlanBTN.Enabled = false;
            }
            else
            {
                lathatatlanBTN.Enabled = true;
            }
        }

        private void ugrasBtn_Click(object sender, EventArgs e)
        {
            if (ugrasBtn.Location == ugrasBtnOriginalLocation)
            {
                ugrasBtn.Location = new Point(0, 0);
            }
            else
            {
                ugrasBtn.Location = ugrasBtnOriginalLocation;
                MessageBox.Show("Sikerült!", "Ügyes vagy", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
        }

        private void ugrasBtn_LocationChanged(object sender, EventArgs e)
        {

        }

        private void Form1_MouseMove(object sender, MouseEventArgs e)
        {
            EgerPozLbl.Text = $"X: {e.X}, Y: {e.Y}";
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            EgerPozLbl.Text = "Mozgasd az egeret!";
        }

        private void label1_Click(object sender, EventArgs e)
        {

        }
    }
}
