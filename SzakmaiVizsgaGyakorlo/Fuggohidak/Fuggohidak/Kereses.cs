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
    public partial class Kereses : Form
    {
        public Kereses()
        {
            InitializeComponent();
        }
        List<Fuggohid> adatok;
        Form1 mainForm;

        public Kereses(List<Fuggohid> adatok, Form1 mainForm)
        {
            InitializeComponent();
            this.adatok = adatok;
            this.mainForm = mainForm;
        }

        private void Kereses_Load(object sender, EventArgs e)
        {

        }
    }
}
