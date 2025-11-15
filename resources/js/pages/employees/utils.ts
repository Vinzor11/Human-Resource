import { FormData } from './types';

export const formatDate = (dateStr: string): string => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return isNaN(date.getTime()) ? '' : date.toISOString().split('T')[0];
};

export const updateSection = <T>(
  section: keyof FormData,
  index: number,
  field: string,
  value: any,
  data: FormData,
  setData: (key: keyof FormData, value: any) => void
) => {
  const arr = [...(data[section] as any[])];
  arr[index] = { ...arr[index], [field]: value };
  setData(section, arr);
};

export const addRow = <T>(
  section: keyof FormData,
  template: T,
  data: FormData,
  setData: (key: keyof FormData, value: any) => void
) => {
  const arr = [...(data[section] as any[]), template];
  setData(section, arr);
};

export const removeRow = (
  section: keyof FormData,
  index: number,
  data: FormData,
  setData: (key: keyof FormData, value: any) => void
) => {
  const arr = (data[section] as any[]).filter((_, i) => i !== index);
  setData(section, arr);
};

export const handleSameAsResidential = (
  checked: boolean,
  data: FormData,
  setData: (data: Partial<FormData>) => void
) => {
  if (checked) {
    setData({
      perm_house_no: data.res_house_no,
      perm_street: data.res_street,
      perm_subdivision: data.res_subdivision,
      perm_barangay: data.res_barangay,
      perm_city: data.res_city,
      perm_province: data.res_province,
      perm_zip_code: data.res_zip_code
    });
  }
};