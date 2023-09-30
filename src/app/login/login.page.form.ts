import {
  FormBuilder,
  FormControl,
  FormGroup,
  Validators,
} from '@angular/forms';

export class LoginPageForm {
  constructor(private fb: FormBuilder) {}

  createForm(): FormGroup {
    return this.fb.group({
      user: new FormControl('', Validators.required),
      password: new FormControl('', Validators.required),
    });
  }
}
