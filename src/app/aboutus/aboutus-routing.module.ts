import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AboutusPage } from './aboutus.page';

const routes: Routes = [
  {
    path: '',
    component: AboutusPage,
    children: [
      {
        path: 'home',
        loadChildren: () =>
          import('../home/home.module').then((m) => m.HomePageModule),
      },
      {
        path: 'contact',
        loadChildren: () =>
          import('../contact/contact.module').then((m) => m.ContactPageModule),
      },
      {
        path: '',
        redirectTo: '/aboutus/home',
        pathMatch: 'full',
      },
    ],
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AboutusPageRoutingModule {}
