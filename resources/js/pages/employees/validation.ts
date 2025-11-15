import { FormData } from './types';

export const validateForm = (data: FormData): { [key: string]: string } => {
  const errors: { [key: string]: string } = {};

  // Required fields validation
  if (!data.surname) errors.surname = 'Surname is required';
  if (!data.first_name) errors.first_name = 'First name is required';
  if (!data.birth_date) errors.birth_date = 'Date of birth is required';
  if (!data.department_id) errors.department_id = 'Department is required';
  if (!data.position_id) errors.position_id = 'Position is required';
  if (!data.email_address) errors.email_address = 'Email address is required';

  // Format validations
  if (data.email_address && !data.email_address.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/)) {
    errors.email_address = 'Invalid email format';
  }
  if (data.telephone_no && !data.telephone_no.match(/^\+?\d{10,12}$|^\d{3}-\d{3}-\d{4}$/)) {
    errors.telephone_no = 'Invalid telephone number format';
  }
  if (data.mobile_no && !data.mobile_no.match(/^\+?\d{10,12}$|^\d{3}-\d{3}-\d{4}$/)) {
    errors.mobile_no = 'Invalid mobile number format';
  }
  if (data.birth_date && !data.birth_date.match(/^\d{4}-\d{2}-\d{2}$/)) {
    errors.birth_date = 'Invalid date format';
  }

  // Dual citizenship validation
  if (data.citizenship === 'Dual') {
    if (!data.dual_citizenship_country) errors.dual_citizenship_country = 'Dual citizenship country is required';
    if (!data.citizenship_type) errors.citizenship_type = 'Citizenship type is required';
  }

  return errors;
};