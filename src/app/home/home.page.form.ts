import {
  FormBuilder,
  FormControl,
  FormGroup,
  Validators,
} from '@angular/forms';

export class HomePageForm {
  constructor(private fb: FormBuilder) {}

  createForm(): FormGroup {
    return this.fb.group({
      recordDate: new FormControl(Date.now(), Validators.required),
      company: new FormControl('', Validators.required),
      constructionPlace: new FormControl('', Validators.required),
      beginHours: new FormControl(Date.now(), Validators.required),
      endHours: new FormControl(Date.now(), Validators.required),
    });
  }
}
