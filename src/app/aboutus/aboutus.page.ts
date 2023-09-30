import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoadingController } from '@ionic/angular';
import { LoginService } from '../login/login.service';

@Component({
  selector: 'app-aboutus',
  templateUrl: './aboutus.page.html',
  styleUrls: ['./aboutus.page.scss'],
})
export class AboutusPage implements OnInit {
  loading!: HTMLIonLoadingElement;
  employeeName: string | null = localStorage.getItem('Employee');

  constructor(
    private router: Router,
    private loadingCtrl: LoadingController,
    private loginService: LoginService
  ) {}

  ngOnInit() {}

  onExit() {
    this.loadingCtrl
      .create({
        message: 'A sair do servidor...',
      })
      .then((overlay) => {
        this.loading = overlay;
        this.loading.present();
        this.loginService.logout();
        this.router.navigateByUrl('/login', { replaceUrl: true });
      })
      .finally(() => {
        this.loadingCtrl.dismiss();
      });
  }
}
