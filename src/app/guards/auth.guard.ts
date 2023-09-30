import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';

@Injectable({
  providedIn: 'root',
})
export class AuthGuard implements CanActivate {
  constructor(private router: Router) {}

  async canActivate(): Promise<boolean> {
    const isLogged = localStorage.getItem('EmployeeId');
    if (isLogged) {
      return true;
    } else {
      this.router.navigateByUrl('/login', { replaceUrl: true });
      return true;
    }
  }
}
