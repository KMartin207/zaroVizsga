namespace Fuggohidak
{
    partial class Form1
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.openOFD = new System.Windows.Forms.OpenFileDialog();
            this.menuStrip1 = new System.Windows.Forms.MenuStrip();
            this.keresésToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.kilépésToolStripMenuItem = new System.Windows.Forms.ToolStripMenuItem();
            this.hidakLB = new System.Windows.Forms.ListBox();
            this.label1 = new System.Windows.Forms.Label();
            this.helyTBx = new System.Windows.Forms.TextBox();
            this.orszagTBx = new System.Windows.Forms.TextBox();
            this.label2 = new System.Windows.Forms.Label();
            this.hosszTBx = new System.Windows.Forms.TextBox();
            this.label3 = new System.Windows.Forms.Label();
            this.evTBx = new System.Windows.Forms.TextBox();
            this.label4 = new System.Windows.Forms.Label();
            this.kilepesBtn = new System.Windows.Forms.Button();
            this.hidakSzamaGB = new System.Windows.Forms.GroupBox();
            this.ketezer_utanRBtn = new System.Windows.Forms.RadioButton();
            this.ketezer_elottRBtn = new System.Windows.Forms.RadioButton();
            this.label5 = new System.Windows.Forms.Label();
            this.darabTBx = new System.Windows.Forms.TextBox();
            this.menuStrip1.SuspendLayout();
            this.hidakSzamaGB.SuspendLayout();
            this.SuspendLayout();
            // 
            // openOFD
            // 
            this.openOFD.FileName = "openFileDialog1";
            // 
            // menuStrip1
            // 
            this.menuStrip1.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.keresésToolStripMenuItem,
            this.kilépésToolStripMenuItem});
            this.menuStrip1.Location = new System.Drawing.Point(0, 0);
            this.menuStrip1.Name = "menuStrip1";
            this.menuStrip1.Size = new System.Drawing.Size(800, 24);
            this.menuStrip1.TabIndex = 0;
            this.menuStrip1.Text = "menuStrip1";
            // 
            // keresésToolStripMenuItem
            // 
            this.keresésToolStripMenuItem.Name = "keresésToolStripMenuItem";
            this.keresésToolStripMenuItem.Size = new System.Drawing.Size(58, 20);
            this.keresésToolStripMenuItem.Text = "Keresés";
            this.keresésToolStripMenuItem.Click += new System.EventHandler(this.keresésToolStripMenuItem_Click);
            // 
            // kilépésToolStripMenuItem
            // 
            this.kilépésToolStripMenuItem.Name = "kilépésToolStripMenuItem";
            this.kilépésToolStripMenuItem.Size = new System.Drawing.Size(56, 20);
            this.kilépésToolStripMenuItem.Text = "Kilépés";
            this.kilépésToolStripMenuItem.Click += new System.EventHandler(this.kilépésToolStripMenuItem_Click);
            // 
            // hidakLB
            // 
            this.hidakLB.FormattingEnabled = true;
            this.hidakLB.Location = new System.Drawing.Point(12, 45);
            this.hidakLB.Name = "hidakLB";
            this.hidakLB.Size = new System.Drawing.Size(247, 186);
            this.hidakLB.TabIndex = 2;
            this.hidakLB.SelectedIndexChanged += new System.EventHandler(this.hidakLB_SelectedIndexChanged);
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(326, 47);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(28, 13);
            this.label1.TabIndex = 3;
            this.label1.Text = "Hely";
            // 
            // helyTBx
            // 
            this.helyTBx.Enabled = false;
            this.helyTBx.Location = new System.Drawing.Point(394, 47);
            this.helyTBx.Name = "helyTBx";
            this.helyTBx.Size = new System.Drawing.Size(100, 20);
            this.helyTBx.TabIndex = 4;
            // 
            // orszagTBx
            // 
            this.orszagTBx.Enabled = false;
            this.orszagTBx.Location = new System.Drawing.Point(394, 88);
            this.orszagTBx.Name = "orszagTBx";
            this.orszagTBx.Size = new System.Drawing.Size(100, 20);
            this.orszagTBx.TabIndex = 6;
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(326, 88);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(40, 13);
            this.label2.TabIndex = 5;
            this.label2.Text = "Ország";
            // 
            // hosszTBx
            // 
            this.hosszTBx.Enabled = false;
            this.hosszTBx.Location = new System.Drawing.Point(394, 122);
            this.hosszTBx.Name = "hosszTBx";
            this.hosszTBx.Size = new System.Drawing.Size(100, 20);
            this.hosszTBx.TabIndex = 8;
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(326, 122);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(36, 13);
            this.label3.TabIndex = 7;
            this.label3.Text = "Hossz";
            // 
            // evTBx
            // 
            this.evTBx.Enabled = false;
            this.evTBx.Location = new System.Drawing.Point(394, 165);
            this.evTBx.Name = "evTBx";
            this.evTBx.Size = new System.Drawing.Size(100, 20);
            this.evTBx.TabIndex = 10;
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(326, 165);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(20, 13);
            this.label4.TabIndex = 9;
            this.label4.Text = "Év";
            // 
            // kilepesBtn
            // 
            this.kilepesBtn.Location = new System.Drawing.Point(607, 353);
            this.kilepesBtn.Name = "kilepesBtn";
            this.kilepesBtn.Size = new System.Drawing.Size(75, 23);
            this.kilepesBtn.TabIndex = 11;
            this.kilepesBtn.Text = "Kilépés";
            this.kilepesBtn.UseVisualStyleBackColor = true;
            this.kilepesBtn.Click += new System.EventHandler(this.kilepesBtn_Click);
            // 
            // hidakSzamaGB
            // 
            this.hidakSzamaGB.Controls.Add(this.ketezer_utanRBtn);
            this.hidakSzamaGB.Controls.Add(this.ketezer_elottRBtn);
            this.hidakSzamaGB.Controls.Add(this.label5);
            this.hidakSzamaGB.Controls.Add(this.darabTBx);
            this.hidakSzamaGB.Location = new System.Drawing.Point(12, 284);
            this.hidakSzamaGB.Name = "hidakSzamaGB";
            this.hidakSzamaGB.Size = new System.Drawing.Size(247, 110);
            this.hidakSzamaGB.TabIndex = 12;
            this.hidakSzamaGB.TabStop = false;
            this.hidakSzamaGB.Text = "Hidak száma";
            // 
            // ketezer_utanRBtn
            // 
            this.ketezer_utanRBtn.AutoSize = true;
            this.ketezer_utanRBtn.Location = new System.Drawing.Point(19, 54);
            this.ketezer_utanRBtn.Name = "ketezer_utanRBtn";
            this.ketezer_utanRBtn.Size = new System.Drawing.Size(99, 17);
            this.ketezer_utanRBtn.TabIndex = 16;
            this.ketezer_utanRBtn.TabStop = true;
            this.ketezer_utanRBtn.Text = "2000 után épült";
            this.ketezer_utanRBtn.UseVisualStyleBackColor = true;
            this.ketezer_utanRBtn.CheckedChanged += new System.EventHandler(this.ketezer_utanRBtn_CheckedChanged);
            // 
            // ketezer_elottRBtn
            // 
            this.ketezer_elottRBtn.AutoSize = true;
            this.ketezer_elottRBtn.Location = new System.Drawing.Point(19, 31);
            this.ketezer_elottRBtn.Name = "ketezer_elottRBtn";
            this.ketezer_elottRBtn.Size = new System.Drawing.Size(98, 17);
            this.ketezer_elottRBtn.TabIndex = 15;
            this.ketezer_elottRBtn.TabStop = true;
            this.ketezer_elottRBtn.Text = "2000 előtt épült";
            this.ketezer_elottRBtn.UseVisualStyleBackColor = true;
            this.ketezer_elottRBtn.CheckedChanged += new System.EventHandler(this.ketezer_elottRBtn_CheckedChanged);
            // 
            // label5
            // 
            this.label5.AutoSize = true;
            this.label5.Location = new System.Drawing.Point(129, 87);
            this.label5.Name = "label5";
            this.label5.Size = new System.Drawing.Size(36, 13);
            this.label5.TabIndex = 13;
            this.label5.Text = "Darab";
            // 
            // darabTBx
            // 
            this.darabTBx.Enabled = false;
            this.darabTBx.Location = new System.Drawing.Point(6, 84);
            this.darabTBx.Name = "darabTBx";
            this.darabTBx.Size = new System.Drawing.Size(100, 20);
            this.darabTBx.TabIndex = 14;
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(800, 450);
            this.Controls.Add(this.hidakSzamaGB);
            this.Controls.Add(this.kilepesBtn);
            this.Controls.Add(this.evTBx);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.hosszTBx);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.orszagTBx);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.helyTBx);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.hidakLB);
            this.Controls.Add(this.menuStrip1);
            this.MainMenuStrip = this.menuStrip1;
            this.Name = "Form1";
            this.Text = "Függőhidak";
            this.Load += new System.EventHandler(this.Form1_Load);
            this.Shown += new System.EventHandler(this.Form1_Shown);
            this.menuStrip1.ResumeLayout(false);
            this.menuStrip1.PerformLayout();
            this.hidakSzamaGB.ResumeLayout(false);
            this.hidakSzamaGB.PerformLayout();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.OpenFileDialog openOFD;
        private System.Windows.Forms.MenuStrip menuStrip1;
        private System.Windows.Forms.ToolStripMenuItem keresésToolStripMenuItem;
        private System.Windows.Forms.ToolStripMenuItem kilépésToolStripMenuItem;
        private System.Windows.Forms.ListBox hidakLB;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.TextBox helyTBx;
        private System.Windows.Forms.TextBox orszagTBx;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.TextBox hosszTBx;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.TextBox evTBx;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.Button kilepesBtn;
        private System.Windows.Forms.GroupBox hidakSzamaGB;
        private System.Windows.Forms.RadioButton ketezer_utanRBtn;
        private System.Windows.Forms.RadioButton ketezer_elottRBtn;
        private System.Windows.Forms.Label label5;
        private System.Windows.Forms.TextBox darabTBx;
    }
}

