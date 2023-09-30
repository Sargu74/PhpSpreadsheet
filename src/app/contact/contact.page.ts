import { Component, OnInit } from '@angular/core';
import { AlertController, LoadingController } from '@ionic/angular';
import { HttpErrorResponse } from '@angular/common/http';

import { ContactService } from './contact.service';

@Component({
  selector: 'app-contact',
  templateUrl: './contact.page.html',
  styleUrls: ['./contact.page.scss'],
})
export class ContactPage implements OnInit {
  loading!: HTMLIonLoadingElement;
  employeeId: string | null = localStorage.getItem('EmployeeId');
  employeeName: string | null = localStorage.getItem('Employee');
  hoursList: any[] = [];

  constructor(
    private contactService: ContactService,
    private loadingCtrl: LoadingController,
    private alertCtrl: AlertController
  ) {}

  ngOnInit() {}

  ionViewDidEnter(): void {
    this.getRecords(this.employeeId);
  }

  handleRefresh(event: any) {
    this.getRecords(this.employeeId);
    event.target.complete();
  }

  deleteRecord(id: number) {
    this.loadingCtrl
      .create({
        message: 'A apagar registo...',
      })
      .then((overlay) => {
        this.loading = overlay;
        this.loading.present();
      });

    this.contactService.deleteRecord(id).subscribe({
      next: (response: any) => {
        this.loading.dismiss();
        this.presentAlert(
          'Resposta do Servidor',
          `Foi apagado ${response} registo!`
        ).then(() => {
          this.getRecords(this.employeeId);
        });
      },
      error: (error: HttpErrorResponse) => {
        this.loading.dismiss();
        this.presentAlert('Resposta do Servidor', error.message);
      },
    });
  }

  getRecords(id: string | null) {
    this.loadingCtrl
      .create({
        message: 'A carregar dados...',
      })
      .then((overlay) => {
        this.loading = overlay;
        this.loading.present();
      });

    this.contactService.getHours(id).subscribe({
      next: (response: any[]) => {
        if (response) {
          this.hoursList = response;
        } else {
          this.hoursList = [];
        }
      },
      error: (error: HttpErrorResponse) => {
        if (error.status != 404) {
          this.presentAlert('Resposta do Servidor', error.message);
        }
        this.loading.dismiss();
      },
      complete: () => this.loading.dismiss(),
    });
  }

  async presentAlert(header: string, message: string): Promise<boolean> {
    return new Promise((resolve) => {
      const ctl = this.alertCtrl;
      this.alertCtrl
        .create({
          header: header,
          animated: true,
          message: message,
          buttons: [
            {
              text: 'OK',
              handler: () => {
                ctl.dismiss().then(() => {
                  resolve(true);
                });
                return false;
              },
            },
          ],
        })
        .then((dlg) => dlg.present());
    });
  }
}
