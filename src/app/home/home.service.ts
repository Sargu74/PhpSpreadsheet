import { Injectable } from '@angular/core';
import { Observable, throwError, catchError } from 'rxjs';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';

import { environment } from 'src/environments/environment';
import { Construction } from '../models/construction';
import { Company } from '../models/company';

@Injectable({
  providedIn: 'root',
})
export class HomeService {
  baseUrl = environment.urlAddress;

  constructor(private http: HttpClient) {}

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

  getCompany(): Observable<Company[]> {
    const url = `${this.baseUrl}companies/`;
    return this.http.get<Company[]>(url).pipe(catchError(this.errHandler));
  }

  getConstruction(): Observable<Construction[]> {
    const url = `${this.baseUrl}constructions/`;
    return this.http.get<Construction[]>(url).pipe(catchError(this.errHandler));
  }

  newRecord(data: any): Observable<any> {
    const url = `${this.baseUrl}hours/`;
    return this.http.post<any>(url, data).pipe(catchError(this.errHandler));
  }
}
