Option Explicit On

Imports System.IO
Imports System.Configuration

Public Class fContenedor

    Private Const fName As String = "TF - WSR"
    Private ModoAt As Boolean = False

    Private Sub fContenedor_Load(ByVal sender As Object, ByVal e As System.EventArgs) Handles Me.Load
        Dim cmd As String = Command()
        Me.Text = fName

        Select Case cmd.Trim.ToUpper
            Case "/GETPO"
                ModoAt = True
                RecibirPO()
            Case "/SENDPO"
                ModoAt = True
                EnviarPO()
            Case Else
                Me.Opacity = 100
        End Select
    End Sub

    Private Sub WB_Navigated(ByVal sender As Object, ByVal e As System.Windows.Forms.WebBrowserNavigatedEventArgs) Handles WB.Navigated
        Dim Valor As String = "", SearchString = "##FIN_PROCESO##", MyPos As Long = 0
        Try
            Valor = Trim(Me.WB.DocumentText.Replace(vbCr, "").Replace(vbLf, ""))
            MyPos = InStr(Valor, SearchString)
            If MyPos > 0 Then
                Application.Exit()
            Else
                Me.Opacity = 100
            End If
        Catch ex As Exception
            MsgBox(ex.Message, MsgBoxStyle.Critical, fName)
        End Try
    End Sub

    Private Sub SalirToolStripMenuItem_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles SalirToolStripMenuItem.Click
        Application.Exit()
    End Sub

    Private Sub EnviarPO()
        Try
            Me.Text = "Sending PO..."
            Me.Opacity = 0
            Application.DoEvents()
            WB.Navigate(My.Settings.SENDPO)
        Catch ex As Exception
            MsgBox(ex.Message, MsgBoxStyle.Critical, fName)
        End Try
    End Sub

    Private Sub RecibirPO()
        Try
            Me.Text = "Receiving PO..."
            Me.Opacity = 0
            Application.DoEvents()
            WB.Navigate(My.Settings.GETPO)
        Catch ex As Exception
            MsgBox(ex.Message, MsgBoxStyle.Critical, fName)
        End Try
    End Sub

    Private Sub RecibirPoToolStripMenuItem_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles RecibirPoToolStripMenuItem.Click
        Me.RecibirPO()
    End Sub

    Private Sub EnviarPoRecibidasToolStripMenuItem_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles EnviarPoRecibidasToolStripMenuItem.Click
        Me.EnviarPO()
    End Sub
End Class
