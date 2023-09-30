import { Component, OnInit } from '@angular/core';
import { AlertController, LoadingController } from '@ionic/angular';
import { FormBuilder, FormGroup } from '@angular/forms';

import { HomePageForm } from './home.page.form';
import { HomeService } from './home.service';

import { Company } from './../models/company';
import { Construction } from '../models/construction';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage implements OnInit {
  form!: FormGroup;
  loading!: HTMLIonLoadingElement;
  employeeName: string | null = '';
  companyList: Company[] = [];
  constructionList: Construction[] = [];

  constructor(
    private alertCtrl: AlertController,
    private fb: FormBuilder,
    private loadingCtrl: LoadingController,
    private homeService: HomeService
  ) {}

  ngOnInit(): void {
    this.homeService.getConstruction().subscribe({
      next: (response: any) => {
        this.constructionList = response;
      },
      error: (error: any) => this.presentAlert('Erro!', error),
    });
    this.homeService.getCompany().subscribe({
      next: (response: any) => {
        this.companyList = response;
      },
      error: (error: any) => this.presentAlert('Erro!', error),
    });

    this.form = new HomePageForm(this.fb).createForm();
    this.form.reset();
  }

  onReset(): void {
    this.form.reset();
  }

  onSubmit(data: FormData): void {
    const recordDate: string = new Date(this.form.value.recordDate)
      .toISOString()
      .substring(0, 10);

    let laborHours: number =
      (new Date(this.form.value.endHours).getTime() -
        new Date(this.form.value.beginHours).getTime()) /
      3600000;
    if (new Date(this.form.value.beginHours).getHours() < 12) {
      laborHours -= 1;
    }

    const beginHours: string = new Date(this.form.value.beginHours)
      .toString()
      .substring(16, 21);
    const endHours: string = new Date(this.form.value.endHours)
      .toString()
      .substring(16, 21);

    const record = {
      ...data,
      employeeId: localStorage.getItem('EmployeeId'),
      beginHours: beginHours,
      endHours: endHours,
      recordDate: recordDate,
      laborHours: laborHours,
    };

    this.loadingCtrl
      .create({
        message: 'A gravar dados no servidor...',
      })
      .then((overlay) => {
        this.loading = overlay;
        this.loading.present();
      });

    // Method to save new record
    this.homeService.newRecord(record).subscribe({
      next: () => {
        this.loading.dismiss();
        this.presentAlert(
          'Resposta do Servidor',
          'Dados gravados com sucesso!'
        );
      },
      error: (error: Error) => {
        this.loading.dismiss();
        this.presentAlert('Resposta do Servidor', error.message);
      },
    });

    this.onReset();
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
