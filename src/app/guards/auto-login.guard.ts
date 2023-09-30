import { Injectable } from '@angular/core';
import { CanLoad, Router } from '@angular/router';

@Injectable({
  providedIn: 'root',
})
export class AutoLoginGuard implements CanLoad {
  constructor(private router: Router) {}

  async canLoad(): Promise<boolean> {
    const isLogged = localStorage.getItem('EmployeeId');
    return true;
  }
}
