import { FormBuilder } from '@angular/forms';
import { LoginPageForm } from './login.page.form';

describe('LoginPageForm', () => {
  it('should create login form empty', () => {
    const loginPageForm = new LoginPageForm(new FormBuilder());
    const form = loginPageForm.createForm();

    expect(form).not.toBeNull();
    expect(form.get('user')).not.toBeNull();
    expect(form.get('user')?.value).toEqual('');
    expect(form.get('user')?.valid).toBeFalse();
    expect(form.get('password')).not.toBeNull();
    expect(form.get('password')?.value).toEqual('');
    expect(form.get('password')?.valid).toBeFalse();
  });
});
