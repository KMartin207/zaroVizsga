namespace GyakorloBevezetoFeladatok
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
            this.eltunes_btn = new System.Windows.Forms.Button();
            this.lathatatlanBTN = new System.Windows.Forms.Button();
            this.inaktivBtn = new System.Windows.Forms.Button();
            this.kilepesBtn = new System.Windows.Forms.Button();
            this.ugrasBtn = new System.Windows.Forms.Button();
            this.EgerPozLbl = new System.Windows.Forms.Label();
            this.label1 = new System.Windows.Forms.Label();
            this.SuspendLayout();
            // 
            // eltunes_btn
            // 
            this.eltunes_btn.Location = new System.Drawing.Point(106, 116);
            this.eltunes_btn.Name = "eltunes_btn";
            this.eltunes_btn.Size = new System.Drawing.Size(74, 23);
            this.eltunes_btn.TabIndex = 0;
            this.eltunes_btn.Text = "Eltűnés";
            this.eltunes_btn.UseVisualStyleBackColor = true;
            this.eltunes_btn.Click += new System.EventHandler(this.eltunes_btn_Click);
            this.eltunes_btn.MouseEnter += new System.EventHandler(this.button1_MouseEnter);
            this.eltunes_btn.MouseLeave += new System.EventHandler(this.button1_MouseLeave);
            // 
            // lathatatlanBTN
            // 
            this.lathatatlanBTN.Location = new System.Drawing.Point(105, 166);
            this.lathatatlanBTN.Name = "lathatatlanBTN";
            this.lathatatlanBTN.Size = new System.Drawing.Size(75, 23);
            this.lathatatlanBTN.TabIndex = 1;
            this.lathatatlanBTN.Text = "Láthatatlan";
            this.lathatatlanBTN.UseVisualStyleBackColor = true;
            this.lathatatlanBTN.Click += new System.EventHandler(this.lathatatlanBTN_Click);
            // 
            // inaktivBtn
            // 
            this.inaktivBtn.Location = new System.Drawing.Point(105, 218);
            this.inaktivBtn.Name = "inaktivBtn";
            this.inaktivBtn.Size = new System.Drawing.Size(75, 23);
            this.inaktivBtn.TabIndex = 2;
            this.inaktivBtn.Text = "Inaktív";
            this.inaktivBtn.UseVisualStyleBackColor = true;
            this.inaktivBtn.Click += new System.EventHandler(this.inaktivBtn_Click);
            // 
            // kilepesBtn
            // 
            this.kilepesBtn.Location = new System.Drawing.Point(213, 263);
            this.kilepesBtn.Name = "kilepesBtn";
            this.kilepesBtn.Size = new System.Drawing.Size(75, 23);
            this.kilepesBtn.TabIndex = 3;
            this.kilepesBtn.Text = "Kilépés";
            this.kilepesBtn.UseVisualStyleBackColor = true;
            this.kilepesBtn.Click += new System.EventHandler(this.kilepesBtn_Click);
            // 
            // ugrasBtn
            // 
            this.ugrasBtn.Location = new System.Drawing.Point(270, 116);
            this.ugrasBtn.Name = "ugrasBtn";
            this.ugrasBtn.Size = new System.Drawing.Size(75, 23);
            this.ugrasBtn.TabIndex = 4;
            this.ugrasBtn.Text = "Ugrás";
            this.ugrasBtn.UseVisualStyleBackColor = true;
            this.ugrasBtn.LocationChanged += new System.EventHandler(this.ugrasBtn_LocationChanged);
            this.ugrasBtn.Click += new System.EventHandler(this.ugrasBtn_Click);
            // 
            // EgerPozLbl
            // 
            this.EgerPozLbl.AutoSize = true;
            this.EgerPozLbl.Location = new System.Drawing.Point(256, 218);
            this.EgerPozLbl.Name = "EgerPozLbl";
            this.EgerPozLbl.Size = new System.Drawing.Size(24, 13);
            this.EgerPozLbl.TabIndex = 5;
            this.EgerPozLbl.Text = "X Y";
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(267, 176);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(92, 13);
            this.label1.TabIndex = 6;
            this.label1.Text = "Az egér pozíciója:";
            this.label1.Click += new System.EventHandler(this.label1_Click);
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(800, 450);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.EgerPozLbl);
            this.Controls.Add(this.ugrasBtn);
            this.Controls.Add(this.kilepesBtn);
            this.Controls.Add(this.inaktivBtn);
            this.Controls.Add(this.lathatatlanBTN);
            this.Controls.Add(this.eltunes_btn);
            this.Name = "Form1";
            this.Text = "Gyakorló bevezető feladatok";
            this.Load += new System.EventHandler(this.Form1_Load);
            this.MouseEnter += new System.EventHandler(this.Form1_MouseEnter);
            this.MouseMove += new System.Windows.Forms.MouseEventHandler(this.Form1_MouseMove);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button eltunes_btn;
        private System.Windows.Forms.Button lathatatlanBTN;
        private System.Windows.Forms.Button inaktivBtn;
        private System.Windows.Forms.Button kilepesBtn;
        private System.Windows.Forms.Button ugrasBtn;
        private System.Windows.Forms.Label EgerPozLbl;
        private System.Windows.Forms.Label label1;
    }
}

