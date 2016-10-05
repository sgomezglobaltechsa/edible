<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class fContenedor
    Inherits System.Windows.Forms.Form

    'Form reemplaza a Dispose para limpiar la lista de componentes.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Requerido por el Diseñador de Windows Forms
    Private components As System.ComponentModel.IContainer

    'NOTA: el Diseñador de Windows Forms necesita el siguiente procedimiento
    'Se puede modificar usando el Diseñador de Windows Forms.  
    'No lo modifique con el editor de código.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Dim resources As System.ComponentModel.ComponentResourceManager = New System.ComponentModel.ComponentResourceManager(GetType(fContenedor))
        Me.MenuStrip1 = New System.Windows.Forms.MenuStrip
        Me.ArchivoToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem
        Me.SalirToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem
        Me.ProcesoToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem
        Me.RecibirPoToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem
        Me.EnviarPoRecibidasToolStripMenuItem = New System.Windows.Forms.ToolStripMenuItem
        Me.StatusStrip1 = New System.Windows.Forms.StatusStrip
        Me.WB = New System.Windows.Forms.WebBrowser
        Me.MenuStrip1.SuspendLayout()
        Me.SuspendLayout()
        '
        'MenuStrip1
        '
        Me.MenuStrip1.Items.AddRange(New System.Windows.Forms.ToolStripItem() {Me.ArchivoToolStripMenuItem, Me.ProcesoToolStripMenuItem})
        Me.MenuStrip1.Location = New System.Drawing.Point(0, 0)
        Me.MenuStrip1.Name = "MenuStrip1"
        Me.MenuStrip1.Size = New System.Drawing.Size(418, 24)
        Me.MenuStrip1.TabIndex = 0
        Me.MenuStrip1.Text = "MenuStrip1"
        '
        'ArchivoToolStripMenuItem
        '
        Me.ArchivoToolStripMenuItem.DropDownItems.AddRange(New System.Windows.Forms.ToolStripItem() {Me.SalirToolStripMenuItem})
        Me.ArchivoToolStripMenuItem.Name = "ArchivoToolStripMenuItem"
        Me.ArchivoToolStripMenuItem.Size = New System.Drawing.Size(37, 20)
        Me.ArchivoToolStripMenuItem.Text = "File"
        '
        'SalirToolStripMenuItem
        '
        Me.SalirToolStripMenuItem.Name = "SalirToolStripMenuItem"
        Me.SalirToolStripMenuItem.Size = New System.Drawing.Size(152, 22)
        Me.SalirToolStripMenuItem.Text = "&Exit"
        '
        'ProcesoToolStripMenuItem
        '
        Me.ProcesoToolStripMenuItem.DropDownItems.AddRange(New System.Windows.Forms.ToolStripItem() {Me.RecibirPoToolStripMenuItem, Me.EnviarPoRecibidasToolStripMenuItem})
        Me.ProcesoToolStripMenuItem.Name = "ProcesoToolStripMenuItem"
        Me.ProcesoToolStripMenuItem.Size = New System.Drawing.Size(70, 20)
        Me.ProcesoToolStripMenuItem.Text = "Processes"
        '
        'RecibirPoToolStripMenuItem
        '
        Me.RecibirPoToolStripMenuItem.Name = "RecibirPoToolStripMenuItem"
        Me.RecibirPoToolStripMenuItem.Size = New System.Drawing.Size(152, 22)
        Me.RecibirPoToolStripMenuItem.Text = "To receive PO"
        '
        'EnviarPoRecibidasToolStripMenuItem
        '
        Me.EnviarPoRecibidasToolStripMenuItem.Name = "EnviarPoRecibidasToolStripMenuItem"
        Me.EnviarPoRecibidasToolStripMenuItem.Size = New System.Drawing.Size(152, 22)
        Me.EnviarPoRecibidasToolStripMenuItem.Text = "Submit PO"
        '
        'StatusStrip1
        '
        Me.StatusStrip1.Location = New System.Drawing.Point(0, 206)
        Me.StatusStrip1.Name = "StatusStrip1"
        Me.StatusStrip1.Size = New System.Drawing.Size(418, 22)
        Me.StatusStrip1.TabIndex = 1
        Me.StatusStrip1.Text = "StatusStrip1"
        '
        'WB
        '
        Me.WB.Dock = System.Windows.Forms.DockStyle.Fill
        Me.WB.Location = New System.Drawing.Point(0, 24)
        Me.WB.MinimumSize = New System.Drawing.Size(20, 20)
        Me.WB.Name = "WB"
        Me.WB.Size = New System.Drawing.Size(418, 182)
        Me.WB.TabIndex = 2
        '
        'fContenedor
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.BackColor = System.Drawing.SystemColors.ActiveCaption
        Me.ClientSize = New System.Drawing.Size(418, 228)
        Me.Controls.Add(Me.WB)
        Me.Controls.Add(Me.StatusStrip1)
        Me.Controls.Add(Me.MenuStrip1)
        Me.Icon = CType(resources.GetObject("$this.Icon"), System.Drawing.Icon)
        Me.MainMenuStrip = Me.MenuStrip1
        Me.Name = "fContenedor"
        Me.Opacity = 0
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "Container"
        Me.MenuStrip1.ResumeLayout(False)
        Me.MenuStrip1.PerformLayout()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub
    Friend WithEvents MenuStrip1 As System.Windows.Forms.MenuStrip
    Friend WithEvents StatusStrip1 As System.Windows.Forms.StatusStrip
    Friend WithEvents ArchivoToolStripMenuItem As System.Windows.Forms.ToolStripMenuItem
    Friend WithEvents SalirToolStripMenuItem As System.Windows.Forms.ToolStripMenuItem
    Friend WithEvents WB As System.Windows.Forms.WebBrowser
    Friend WithEvents ProcesoToolStripMenuItem As System.Windows.Forms.ToolStripMenuItem
    Friend WithEvents RecibirPoToolStripMenuItem As System.Windows.Forms.ToolStripMenuItem
    Friend WithEvents EnviarPoRecibidasToolStripMenuItem As System.Windows.Forms.ToolStripMenuItem

End Class
