import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { BehaviorSubject, catchError, Observable, tap, throwError } from 'rxjs';

import { environment } from './../../environments/environment';
import { Employee } from './../models/employee';

@Injectable({
  providedIn: 'root',
})
export class LoginService {
  baseUrl = environment.urlAddress;

  constructor(private http: HttpClient) {}
  isAuthenticated: BehaviorSubject<boolean | null> = new BehaviorSubject<
    boolean | null
  >(null);

  errHandler(error: HttpErrorResponse): Observable<never> {
    let errorMessage: string = '';

    if (error.status === 0) {
      // client-side error
      errorMessage = `${error.message}`;
    } else {
      // server-side error
      errorMessage = `${error.error.message}`;
    }
    return throwError(() => new Error(errorMessage));
  }

  login(credentials: { user: string; password: string }): Observable<Employee> {
    const url = `${this.baseUrl}employees/${credentials.user}`;

    return this.http.get<Employee>(url).pipe(
      tap((_) => {
        this.isAuthenticated.next(true);
      }),
      catchError(this.errHandler)
    );
  }

  logout() {
    localStorage.clear();
    this.isAuthenticated.next(false);
  }
}
