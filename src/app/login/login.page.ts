import { Component, OnInit } from '@angular/core';
import { AlertController, LoadingController } from '@ionic/angular';
import { FormGroup, FormBuilder } from '@angular/forms';
import { HttpErrorResponse } from '@angular/common/http';
import { Router } from '@angular/router';

import { LoginPageForm } from './login.page.form';
import { LoginService } from './login.service';
import { Employee } from '../models/employee';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {
  form!: FormGroup;
  loading!: HTMLIonLoadingElement;
  loadingController!: LoadingController;

  constructor(
    private fb: FormBuilder,
    private alertCtrl: AlertController,
    private loadingCtrl: LoadingController,
    private router: Router,
    private loginService: LoginService
  ) {}

  ngOnInit(): void {
    this.form = new LoginPageForm(this.fb).createForm();
    this.form.reset({
      user: '',
      password: '',
    });
  }

  onClick(credencials: { user: string; password: string }): void {
    this.loadingCtrl
      .create({
        message: 'A autenticar no servidor...',
      })
      .then((overlay) => {
        this.loading = overlay;
        this.loading.present();

        if (credencials) {
          this.loginService.login(credencials).subscribe({
            next: (user) => {
              if (credencials.password === user.Password) {
                this.saveCredencials(user);
                // this.form.reset();

                this.loading.dismiss();
                this.router.navigateByUrl('/aboutus', { replaceUrl: true });
              } else {
                this.presentAlert(
                  'Resposta do servidor',
                  'Utilizador ou Password inválida!'
                );
              }
            },
            error: (error) => {
              if (error instanceof HttpErrorResponse) {
                if (error.error instanceof ErrorEvent) {
                  // Client-side error
                  this.presentAlert(
                    'Resposta do Servidor',
                    error.error.message
                  );
                } else {
                  this.presentAlert(
                    'Resposta do Servidor',
                    'Não foi possível executar a operação! Por favor tente mais tarde.'
                  );
                }
              } else {
                this.presentAlert(
                  'Resposta do Servidor',
                  'Não foi possível conectar-se ao Servidor. Verifique por favor a sua conexão à Internet!'
                );
              }
            },
          });
        }
        this.loading.dismiss();
      });
  }

  saveCredencials(credencials: Employee) {
    localStorage.setItem('EmployeeId', credencials.ID.toString());
    localStorage.setItem('Employee', credencials.Employee);
  }

  async presentAlert(header: string, message: string) {
    const alert = await this.alertCtrl.create({
      header: header,
      message: message,
      buttons: ['OK'],
    });

    await alert.present();
  }
}
