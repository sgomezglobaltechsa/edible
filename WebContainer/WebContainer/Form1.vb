Option Explicit On

Imports System.IO
Imports System.Configuration

Public Class fContenedor

    Private Const fName As String = "WebContainer"
    Private Path As String = System.IO.Path.GetDirectoryName( System.Reflection.Assembly.GetExecutingAssembly().GetName().CodeBase)


    Private Sub fContenedor_Load(ByVal sender As Object, ByVal e As System.EventArgs) Handles Me.Load
        Dim cmd As String = Command()
        Select Case cmd
            Case "/GETPO"
                WB.Navigate(My.Settings.GETPO)
            Case "/SETPO"
                WB.Navigate(My.Settings.SETPO)
            Case Else
                Application.Exit()
        End Select

    End Sub

    Private Sub WB_Navigated(ByVal sender As Object, ByVal e As System.Windows.Forms.WebBrowserNavigatedEventArgs) Handles WB.Navigated
        Dim Valor As String = ""
        Try
            Valor = Trim(Me.WB.DocumentText.Replace(vbCr, "").Replace(vbLf, ""))
            If Valor = "##FIN##" Then Application.Exit()
        Catch ex As Exception
            MsgBox(ex.Message, MsgBoxStyle.Critical, fName)
        End Try
    End Sub

    Private Sub SalirToolStripMenuItem_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles SalirToolStripMenuItem.Click
        Application.Exit()
    End Sub

End Class
