namespace Fuggohidak
{
    partial class Form2
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
            this.keresesLB = new System.Windows.Forms.ListBox();
            this.keresesGBx = new System.Windows.Forms.GroupBox();
            this.bezarasBtn = new System.Windows.Forms.Button();
            this.keresesBtn = new System.Windows.Forms.Button();
            this.keresesCheckBox = new System.Windows.Forms.CheckBox();
            this.keresesComboBox = new System.Windows.Forms.ComboBox();
            this.label1 = new System.Windows.Forms.Label();
            this.backgroundWorker1 = new System.ComponentModel.BackgroundWorker();
            this.keresesGBx.SuspendLayout();
            this.SuspendLayout();
            // 
            // keresesLB
            // 
            this.keresesLB.FormattingEnabled = true;
            this.keresesLB.Location = new System.Drawing.Point(25, 23);
            this.keresesLB.Name = "keresesLB";
            this.keresesLB.Size = new System.Drawing.Size(381, 147);
            this.keresesLB.TabIndex = 0;
            this.keresesLB.SelectedIndexChanged += new System.EventHandler(this.keresesLB_SelectedIndexChanged);
            // 
            // keresesGBx
            // 
            this.keresesGBx.Controls.Add(this.bezarasBtn);
            this.keresesGBx.Controls.Add(this.keresesBtn);
            this.keresesGBx.Controls.Add(this.keresesCheckBox);
            this.keresesGBx.Controls.Add(this.keresesComboBox);
            this.keresesGBx.Controls.Add(this.label1);
            this.keresesGBx.Location = new System.Drawing.Point(25, 235);
            this.keresesGBx.Name = "keresesGBx";
            this.keresesGBx.Size = new System.Drawing.Size(381, 185);
            this.keresesGBx.TabIndex = 1;
            this.keresesGBx.TabStop = false;
            this.keresesGBx.Text = "Keresés";
            // 
            // bezarasBtn
            // 
            this.bezarasBtn.Location = new System.Drawing.Point(194, 129);
            this.bezarasBtn.Name = "bezarasBtn";
            this.bezarasBtn.Size = new System.Drawing.Size(75, 23);
            this.bezarasBtn.TabIndex = 4;
            this.bezarasBtn.Text = "Bezárás";
            this.bezarasBtn.UseVisualStyleBackColor = true;
            this.bezarasBtn.Click += new System.EventHandler(this.bezarasBtn_Click);
            // 
            // keresesBtn
            // 
            this.keresesBtn.Location = new System.Drawing.Point(73, 129);
            this.keresesBtn.Name = "keresesBtn";
            this.keresesBtn.Size = new System.Drawing.Size(75, 23);
            this.keresesBtn.TabIndex = 3;
            this.keresesBtn.Text = "Keresés";
            this.keresesBtn.UseVisualStyleBackColor = true;
            this.keresesBtn.Click += new System.EventHandler(this.keresesBtn_Click);
            // 
            // keresesCheckBox
            // 
            this.keresesCheckBox.AutoSize = true;
            this.keresesCheckBox.Location = new System.Drawing.Point(20, 79);
            this.keresesCheckBox.Name = "keresesCheckBox";
            this.keresesCheckBox.Size = new System.Drawing.Size(149, 17);
            this.keresesCheckBox.TabIndex = 2;
            this.keresesCheckBox.Text = "1 km-nél nem hosszabbak";
            this.keresesCheckBox.UseVisualStyleBackColor = true;
            this.keresesCheckBox.CheckedChanged += new System.EventHandler(this.keresesCheckBox_CheckedChanged);
            // 
            // keresesComboBox
            // 
            this.keresesComboBox.FormattingEnabled = true;
            this.keresesComboBox.Location = new System.Drawing.Point(114, 43);
            this.keresesComboBox.Name = "keresesComboBox";
            this.keresesComboBox.Size = new System.Drawing.Size(121, 21);
            this.keresesComboBox.TabIndex = 1;
            this.keresesComboBox.SelectedIndexChanged += new System.EventHandler(this.keresesComboBox_SelectedIndexChanged);
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(17, 43);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(40, 13);
            this.label1.TabIndex = 0;
            this.label1.Text = "Ország";
            // 
            // Form2
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(800, 450);
            this.Controls.Add(this.keresesGBx);
            this.Controls.Add(this.keresesLB);
            this.Name = "Form2";
            this.Text = "Form2";
            this.Load += new System.EventHandler(this.Form2_Load);
            this.keresesGBx.ResumeLayout(false);
            this.keresesGBx.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.ListBox keresesLB;
        private System.Windows.Forms.GroupBox keresesGBx;
        private System.Windows.Forms.CheckBox keresesCheckBox;
        private System.Windows.Forms.ComboBox keresesComboBox;
        private System.Windows.Forms.Label label1;
        private System.ComponentModel.BackgroundWorker backgroundWorker1;
        private System.Windows.Forms.Button bezarasBtn;
        private System.Windows.Forms.Button keresesBtn;
    }
}