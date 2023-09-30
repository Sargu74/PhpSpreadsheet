import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';

import { environment } from 'src/environments/environment';
import { catchError, Observable, throwError } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class ContactService {
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

    return throwError(() => {
      const error$: any = new Error(errorMessage);
      error$.status = error.status || 500;
      return error$;
    });
  }

  deleteRecord(id: number): Observable<any> {
    const url = `${this.baseUrl}hours/${id}`;
    return this.http.delete<any>(url).pipe(catchError(this.errHandler));
  }

  getHours(id: string | null): Observable<any[]> {
    const url = `${this.baseUrl}hours/${id}`;
    return this.http.get<any[]>(url).pipe(catchError(this.errHandler));
  }
}
