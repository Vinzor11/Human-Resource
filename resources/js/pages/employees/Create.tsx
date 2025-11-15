import AppLayout from '@/layouts/app-layout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { toast } from '@/components/custom-toast';
import { useState, useEffect, useRef } from 'react';
import { PageProps } from '@/types';

type CreateEmployeeProps = {
  employee?: {
    id: string;
    surname: string;
    first_name: string;
    middle_name: string;
    name_extension: string;
    status: string;
    employee_type: string;
    department_id: string;
    position_id: string;
    birth_date: string;
    birth_place: string;
    sex: string;
    civil_status: string;
    height_m: string;
    weight_kg: string;
    blood_type: string;
    gsis_id_no: string;
    pagibig_id_no: string;
    philhealth_no: string;
    sss_no: string;
    tin_no: string;
    agency_employee_no: string;
    citizenship: string;
    dual_citizenship: boolean;
    citizenship_type: string;
    dual_citizenship_country: string;
    res_house_no: string;
    res_street: string;
    res_subdivision: string;
    res_barangay: string;
    res_city: string;
    res_province: string;
    res_zip_code: string;
    perm_house_no: string;
    perm_street: string;
    perm_subdivision: string;
    perm_barangay: string;
    perm_city: string;
    perm_province: string;
    perm_zip_code: string;
    telephone_no: string;
    mobile_no: string;
    email_address: string;
    government_issued_id: string;
    id_number: string;
    id_date_issued: string;
    id_place_of_issue: string;
    indigenous_group: string;
    pwd_id_no: string;
    solo_parent_id_no: string;
    family_background: Array<{
      relation: string;
      surname: string;
      first_name: string;
      middle_name: string;
      name_extension: string;
      occupation: string;
      employer: string;
      business_address: string;
      telephone_no: string;
    }>;
    children: Array<{
      full_name: string;
      birth_date: string;
    }>;
    educational_background: Array<{
      level: string;
      school_name: string;
      degree_course: string;
      period_from: string;
      period_to: string;
      highest_level_units: string;
      year_graduated: string;
      honors_received: string;
    }>;
    civil_service_eligibility: Array<{
      eligibility: string;
      rating: string;
      exam_date: string;
      exam_place: string;
      license_no: string;
      license_validity: string;
    }>;
    work_experience: Array<{
      position_title: string;
      company_name: string;
      company_address: string;
      date_from: string;
      date_to: string;
      monthly_salary: string;
      salary_grade_step: string;
      status_of_appointment: string;
      is_gov_service: boolean;
    }>;
    voluntary_work: Array<{
      organization_name: string;
      organization_address: string;
      date_from: string;
      date_to: string;
      hours_rendered: string;
      position_or_nature: string;
    }>;
    learning_development: Array<{
      title: string;
      date_from: string;
      date_to: string;
      hours: string;
      type_of_ld: string;
      conducted_by: string;
    }>;
    other_information: {
      skill_or_hobby: string;
      non_academic_distinctions: string;
      memberships: string;
    };
    references: Array<{
      first_name: string;
      middle_initial: string;
      surname: string;
      address: string;
      telephone_no: string;
    }>;
    questionnaire: Array<{
      question_number: number;
      answer: boolean;
      details: string;
    }>;
  };
  departments: { id: number; faculty_name: string }[];
  positions: { id: number; pos_name: string }[];
  mode?: 'create' | 'edit' | 'view';
};

// Helper function to format dates without time
const formatDate = (dateString: string): string => {
  if (!dateString) return '';
  if (dateString.includes('T')) {
    return dateString.split('T')[0];
  }
  return dateString;
};

export default function CreateEmployee({ employee, departments, positions, mode = 'create' }: CreateEmployeeProps) {
  const { csrf, errors } = usePage<PageProps>().props;
  const isEdit = !!employee;
  const isView = mode === 'view';
  const formRef = useRef<HTMLFormElement>(null);
  
  // Format initial dates to remove time component
  const initialData = {
    id: employee?.id || '',
    surname: employee?.surname || '',
    first_name: employee?.first_name || '',
    middle_name: employee?.middle_name || '',
    name_extension: employee?.name_extension || '',
    status: employee?.status || 'active',
    employee_type: employee?.employee_type || 'Teaching',
    department_id: employee?.department_id?.toString() || '',
    position_id: employee?.position_id?.toString() || '',
    birth_date: formatDate(employee?.birth_date || ''),
    birth_place: employee?.birth_place || '',
    sex: employee?.sex || 'Male',
    civil_status: employee?.civil_status || '',
    height_m: employee?.height_m || '',
    weight_kg: employee?.weight_kg || '',
    blood_type: employee?.blood_type || '',
    gsis_id_no: employee?.gsis_id_no || '',
    pagibig_id_no: employee?.pagibig_id_no || '',
    philhealth_no: employee?.philhealth_no || '',
    sss_no: employee?.sss_no || '',
    tin_no: employee?.tin_no || '',
    agency_employee_no: employee?.agency_employee_no || '',
    citizenship: employee?.citizenship || 'Filipino',
    dual_citizenship: employee?.dual_citizenship || false,
    citizenship_type: employee?.citizenship_type || '',
    dual_citizenship_country: employee?.dual_citizenship_country || '',
    res_house_no: employee?.res_house_no || '',
    res_street: employee?.res_street || '',
    res_subdivision: employee?.res_subdivision || '',
    res_barangay: employee?.res_barangay || '',
    res_city: employee?.res_city || '',
    res_province: employee?.res_province || '',
    res_zip_code: employee?.res_zip_code || '',
    perm_house_no: employee?.perm_house_no || '',
    perm_street: employee?.perm_street || '',
    perm_subdivision: employee?.perm_subdivision || '',
    perm_barangay: employee?.perm_barangay || '',
    perm_city: employee?.perm_city || '',
    perm_province: employee?.perm_province || '',
    perm_zip_code: employee?.perm_zip_code || '',
    telephone_no: employee?.telephone_no || '',
    mobile_no: employee?.mobile_no || '',
    email_address: employee?.email_address || '',
    government_issued_id: employee?.government_issued_id || '',
    id_number: employee?.id_number || '',
    id_date_issued: formatDate(employee?.id_date_issued || ''),
    id_place_of_issue: employee?.id_place_of_issue || '',
    indigenous_group: employee?.indigenous_group || '',
    pwd_id_no: employee?.pwd_id_no || '',
    solo_parent_id_no: employee?.solo_parent_id_no || '',

    family_background: employee?.family_background || [
      { relation: 'Father', surname: '', first_name: '', middle_name: '', name_extension: '', occupation: '', employer: '', business_address: '', telephone_no: '' },
      { relation: 'Mother', surname: '', first_name: '', middle_name: '', name_extension: '', occupation: '', employer: '', business_address: '', telephone_no: '' }
    ],
    children: employee?.children?.map(child => ({
      full_name: child.full_name || '',
      birth_date: formatDate(child.birth_date || '')
    })) || [],
    educational_background: employee?.educational_background?.map(edu => ({
      level: edu.level || '',
      school_name: edu.school_name || '',
      degree_course: edu.degree_course || '',
      period_from: formatDate(edu.period_from || ''),
      period_to: formatDate(edu.period_to || ''),
      highest_level_units: edu.highest_level_units || '',
      year_graduated: edu.year_graduated || '',
      honors_received: edu.honors_received || '',
    })) || [],
    civil_service_eligibility: employee?.civil_service_eligibility?.map(cse => ({
      eligibility: cse.eligibility || '',
      rating: cse.rating || '',
      exam_date: formatDate(cse.exam_date || ''),
      exam_place: cse.exam_place || '',
      license_no: cse.license_no || '',
      license_validity: formatDate(cse.license_validity || ''),
    })) || [],
    work_experience: employee?.work_experience?.map(work => ({
      position_title: work.position_title || '',
      company_name: work.company_name || '',
      company_address: work.company_address || '',
      date_from: formatDate(work.date_from || ''),
      date_to: formatDate(work.date_to || ''),
      monthly_salary: work.monthly_salary || '',
      salary_grade_step: work.salary_grade_step || '',
      status_of_appointment: work.status_of_appointment || '',
      is_gov_service: work.is_gov_service || false,
    })) || [{
      position_title: '', 
      company_name: '', 
      company_address: '',
      date_from: '', 
      date_to: '', 
      monthly_salary: '', 
      salary_grade_step: '', 
      status_of_appointment: '', 
      is_gov_service: false
    }],
    voluntary_work: employee?.voluntary_work?.map(vw => ({
      organization_name: vw.organization_name || '',
      organization_address: vw.organization_address || '',
      date_from: formatDate(vw.date_from || ''),
      date_to: formatDate(vw.date_to || ''),
      hours_rendered: vw.hours_rendered || '',
      position_or_nature: vw.position_or_nature || '',
    })) || [{
      organization_name: '', 
      organization_address: '',
      date_from: '', 
      date_to: '', 
      hours_rendered: '', 
      position_or_nature: ''
    }],
    learning_development: employee?.learning_development?.map(ld => ({
      title: ld.title || '',
      date_from: formatDate(ld.date_from || ''),
      date_to: formatDate(ld.date_to || ''),
      hours: ld.hours || '',
      type_of_ld: ld.type_of_ld || '',
      conducted_by: ld.conducted_by || '',
    })) || [],
    other_information: employee?.other_information || {
      skill_or_hobby: '',
      non_academic_distinctions: '',
      memberships: ''
    },
    references: employee?.references?.map(ref => ({
      first_name: ref.first_name || '',
      middle_initial: ref.middle_initial || '',
      surname: ref.surname || '',
      address: ref.address || '',
      telephone_no: ref.telephone_no || '',
    })) || [],
    questionnaire: employee?.questionnaire || [
      { question_number: 34, answer: false, details: '' },
      { question_number: 35, answer: false, details: '' },
      { question_number: 36, answer: false, details: '' },
      { question_number: 37, answer: false, details: '' },
      { question_number: 38, answer: false, details: '' },
      { question_number: 39, answer: false, details: '' },
      { question_number: 40, answer: false, details: '' },
      { question_number: 41, answer: false, details: '' },
    ]
  };

  const { data, setData, processing, post, put, reset } = useForm(initialData);

  // Scroll to first error field
  useEffect(() => {
    if (Object.keys(errors).length > 0 && formRef.current) {
      const firstError = Object.keys(errors)[0];
      const errorElement = document.getElementById(firstError);
      
      if (errorElement) {
        errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        errorElement.focus({ preventScroll: true });
      }
    }
  }, [errors]);

  const handleSubmit = (e: React.FormEvent) => {
    if (isView) {
      e.preventDefault();
      return;
    }
    
    e.preventDefault();
    const method = isEdit ? put : post;
    const routeName = isEdit ? 'employees.update' : 'employees.store';
    const routeParams = isEdit ? { employee: employee?.id } : {};

    method(route(routeName, routeParams), {
      onSuccess: () => {
        toast.success(`Employee ${isEdit ? 'updated' : 'created'} successfully.`);
      },
      onError: (errors) => {
        console.error('Submission errors:', errors);
        toast.error(`Failed to ${isEdit ? 'update' : 'create'} employee. Please check the form for errors.`);
      },
      preserveScroll: true,
    });
  };

  const updateSection = (section: string, index: number, field: string, value: any) => {
    if (isView) return;
    
    const arr = [...(data as any)[section]];
    arr[index] = { ...arr[index], [field]: value };
    setData(section as any, arr);
  };

  const addRow = (section: string, template: any) => {
    if (isView) return;
    
    const arr = [...(data as any)[section], template];
    setData(section as any, arr);
  };

  const removeRow = (section: string, idx: number) => {
    if (isView) return;
    
    const arr = (data as any)[section].filter((_: any, i: number) => i !== idx);
    setData(section as any, arr);
  };

  const handleSameAsResidential = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (isView) return;
    
    if (e.target.checked) {
      setData({
        ...data,
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

  // Handle citizenship change
  useEffect(() => {
    if (isView) return;
    
    if (data.citizenship !== 'Dual') {
      setData({
        ...data,
        dual_citizenship: false,
        dual_citizenship_country: '',
        citizenship_type: ''
      });
    } else {
      setData({
        ...data,
        dual_citizenship: true
      });
    }
  }, [data.citizenship]);

  // Helper function to get error message for a field
  const getError = (field: string) => {
    return errors[field] ? (
      <p className="text-red-500 text-xs mt-1">{errors[field]}</p>
    ) : null;
  };

  return (
    <AppLayout>
      <Head title={isView ? "View Employee" : isEdit ? "Edit Employee" : "Create Employee"} />
      <form ref={formRef} onSubmit={handleSubmit} className="p-4 max-w-7xl mx-auto" method="POST">
        {/* CSRF Token */}
        <input type="hidden" name="_token" value={csrf} />
        
        {/* Header */}
        <div className="text-center mb-6">
          <h1 className="text-xl font-bold uppercase">PERSONAL DATA SHEET</h1>
          <p className="text-xs italic text-red-600 mb-2">
            WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.
          </p>
          <p className="text-xs mb-4">
            READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.
          </p>
          <p className="text-xs">
            Print legibly. Tick appropriate boxes ( ) and use separate sheet if necessary. Indicate N/A if not applicable. DO NOT ABBREVIATE.
          </p>
        </div>

        {/* I. PERSONAL INFORMATION */}
        <div className="border border-black p-4 mb-6">
          <h2 className="font-bold mb-4">I. PERSONAL INFORMATION</h2>
          
          <div className="grid grid-cols-4 gap-4 mb-4">
            <div className="col-span-1">
              <label className="block text-sm font-medium">1. EMPLOYEE ID</label>
              <input 
                id="id"
                value={data.id} 
                onChange={e => setData('id', e.target.value)} 
                className={`w-full border-b ${errors.id ? 'border-red-500' : 'border-black'}`} 
                placeholder="Employee ID"
                disabled={isEdit || isView}
                readOnly={isView}
              />
              {getError('id')}
            </div>
          </div>
          
          <div className="grid grid-cols-4 gap-4 mb-4">
            <div className="col-span-1">
              <label className="block text-sm font-medium">2. SURNAME</label>
              <input 
                id="surname"
                value={data.surname} 
                onChange={e => setData('surname', e.target.value)} 
                className={`w-full border-b ${errors.surname ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('surname')}
            </div>
            <div className="col-span-2">
              <label className="block text-sm font-medium">FIRST NAME</label>
              <input 
                id="first_name"
                value={data.first_name} 
                onChange={e => setData('first_name', e.target.value)} 
                className={`w-full border-b ${errors.first_name ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('first_name')}
            </div>
            <div>
              <label className="block text-sm font-medium">NAME EXTENSION (JR., SR)</label>
              <input 
                id="name_extension"
                value={data.name_extension} 
                onChange={e => setData('name_extension', e.target.value)} 
                className={`w-full border-b ${errors.name_extension ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('name_extension')}
            </div>
          </div>
          
          <div className="mb-4">
            <label className="block text-sm font-medium">MIDDLE NAME</label>
            <input 
              id="middle_name"
              value={data.middle_name} 
              onChange={e => setData('middle_name', e.target.value)} 
              className={`w-1/2 border-b ${errors.middle_name ? 'border-red-500' : 'border-black'}`} 
              readOnly={isView}
            />
            {getError('middle_name')}
          </div>
          
          <div className="grid grid-cols-4 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">3. DATE OF BIRTH (mm/dd/yyyy)</label>
              <input 
                id="birth_date"
                type="date" 
                value={data.birth_date} 
                onChange={e => setData('birth_date', e.target.value)} 
                className={`w-full border-b ${errors.birth_date ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('birth_date')}
            </div>
            <div className="col-span-3">
              <label className="block text-sm font-medium">4. PLACE OF BIRTH</label>
              <input 
                id="birth_place"
                value={data.birth_place} 
                onChange={e => setData('birth_place', e.target.value)} 
                className={`w-full border-b ${errors.birth_place ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('birth_place')}
            </div>
          </div>
          
          <div className="grid grid-cols-4 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">5. SEX</label>
              <select 
                id="sex"
                value={data.sex} 
                onChange={e => setData('sex', e.target.value)} 
                className={`w-full border-b ${errors.sex ? 'border-red-500' : 'border-black'}`}
                disabled={isView}
              >
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
              {getError('sex')}
            </div>
            <div>
              <label className="block text-sm font-medium">6. CIVIL STATUS</label>
              <select 
                id="civil_status"
                value={data.civil_status} 
                onChange={e => setData('civil_status', e.target.value)} 
                className={`w-full border-b ${errors.civil_status ? 'border-red-500' : 'border-black'}`}
                disabled={isView}
              >
                <option value="">Select Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Separated">Separated</option>
                <option value="Widowed">Widowed</option>
                <option value="Annulled">Annulled</option>
              </select>
              {getError('civil_status')}
            </div>
            <div>
              <label className="block text-sm font-medium">7. HEIGHT (m)</label>
              <input 
                id="height_m"
                type="number" 
                step="0.01" 
                value={data.height_m} 
                onChange={e => setData('height_m', e.target.value)} 
                className={`w-full border-b ${errors.height_m ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('height_m')}
            </div>
            <div>
              <label className="block text-sm font-medium">8. WEIGHT (kg)</label>
              <input 
                id="weight_kg"
                type="number" 
                step="0.1" 
                value={data.weight_kg} 
                onChange={e => setData('weight_kg', e.target.value)} 
                className={`w-full border-b ${errors.weight_kg ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('weight_kg')}
            </div>
          </div>
          
          <div className="grid grid-cols-4 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">9. BLOOD TYPE</label>
              <input 
                id="blood_type"
                value={data.blood_type} 
                onChange={e => setData('blood_type', e.target.value)} 
                className={`w-full border-b ${errors.blood_type ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('blood_type')}
            </div>
            <div>
              <label className="block text-sm font-medium">10. GSIS ID NO.</label>
              <input 
                id="gsis_id_no"
                value={data.gsis_id_no} 
                onChange={e => setData('gsis_id_no', e.target.value)} 
                className={`w-full border-b ${errors.gsis_id_no ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('gsis_id_no')}
            </div>
            <div>
              <label className="block text-sm font-medium">11. PAG-IBIG ID NO.</label>
              <input 
                id="pagibig_id_no"
                value={data.pagibig_id_no} 
                onChange={e => setData('pagibig_id_no', e.target.value)} 
                className={`w-full border-b ${errors.pagibig_id_no ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('pagibig_id_no')}
            </div>
            <div>
              <label className="block text-sm font-medium">12. PHILHEALTH NO.</label>
              <input 
                id="philhealth_no"
                value={data.philhealth_no} 
                onChange={e => setData('philhealth_no', e.target.value)} 
                className={`w-full border-b ${errors.philhealth_no ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('philhealth_no')}
            </div>
          </div>
          
          <div className="grid grid-cols-3 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">13. SSS NO.</label>
              <input 
                id="sss_no"
                value={data.sss_no} 
                onChange={e => setData('sss_no', e.target.value)} 
                className={`w-full border-b ${errors.sss_no ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('sss_no')}
            </div>
            <div>
              <label className="block text-sm font-medium">14. TIN NO.</label>
              <input 
                id="tin_no"
                value={data.tin_no} 
                onChange={e => setData('tin_no', e.target.value)} 
                className={`w-full border-b ${errors.tin_no ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('tin_no')}
            </div>
            <div>
              <label className="block text-sm font-medium">15. AGENCY EMPLOYEE NO.</label>
              <input 
                id="agency_employee_no"
                value={data.agency_employee_no} 
                onChange={e => setData('agency_employee_no', e.target.value)} 
                className={`w-full border-b ${errors.agency_employee_no ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('agency_employee_no')}
            </div>
          </div>
          
          <div className="grid grid-cols-3 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">16. CITIZENSHIP</label>
              <select 
                id="citizenship"
                value={data.citizenship} 
                onChange={e => {
                  setData('citizenship', e.target.value);
                  setData('dual_citizenship', e.target.value === 'Dual');
                }} 
                className={`w-full border-b ${errors.citizenship ? 'border-red-500' : 'border-black'}`}
                disabled={isView}
              >
                <option value="Filipino">Filipino</option>
                <option value="Dual">Dual Citizenship</option>
                <option value="Other">Other</option>
              </select>
              {getError('citizenship')}
              {data.dual_citizenship && (
                <div className="mt-2">
                  <label className="block text-sm font-medium">If holder of dual citizenship, please indicate country:</label>
                  <input 
                    id="dual_citizenship_country"
                    value={data.dual_citizenship_country} 
                    onChange={e => setData('dual_citizenship_country', e.target.value)} 
                    className={`w-full border-b ${errors.dual_citizenship_country ? 'border-red-500' : 'border-black'}`} 
                    readOnly={isView}
                  />
                  {getError('dual_citizenship_country')}
                  <div className="mt-2">
                    <label className="block text-sm font-medium">Citizenship Type:</label>
                    <select 
                      id="citizenship_type"
                      value={data.citizenship_type} 
                      onChange={e => setData('citizenship_type', e.target.value)} 
                      className={`w-full border-b ${errors.citizenship_type ? 'border-red-500' : 'border-black'}`}
                      disabled={isView}
                    >
                      <option value="">Select type</option>
                      <option value="By birth">By birth</option>
                      <option value="By naturalization">By naturalization</option>
                    </select>
                    {getError('citizenship_type')}
                  </div>
                </div>
              )}
            </div>
          </div>
          
          <div className="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">17. DEPARTMENT</label>
              <select 
                id="department_id"
                value={data.department_id} 
                onChange={e => setData('department_id', e.target.value)} 
                className={`w-full border-b ${errors.department_id ? 'border-red-500' : 'border-black'}`}
                disabled={isView}
              >
                <option value="">Select Department</option>
                {departments.map(dept => (
                  <option key={dept.id} value={dept.id.toString()}>{dept.faculty_name}</option>
                ))}
              </select>
              {getError('department_id')}
            </div>
            <div>
              <label className="block text-sm font-medium">18. POSITION</label>
              <select 
                id="position_id"
                value={data.position_id} 
                onChange={e => setData('position_id', e.target.value)} 
                className={`w-full border-b ${errors.position_id ? 'border-red-500' : 'border-black'}`}
                disabled={isView}
              >
                <option value="">Select Position</option>
                {positions.map(pos => (
                  <option key={pos.id} value={pos.id.toString()}>{pos.pos_name}</option>
                ))}
              </select>
              {getError('position_id')}
            </div>
          </div>
          
          <div className="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">STATUS</label>
              <select 
                id="status"
                value={data.status} 
                onChange={e => setData('status', e.target.value)} 
                className={`w-full border-b ${errors.status ? 'border-red-500' : 'border-black'}`}
                disabled={isView}
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="on-leave">On Leave</option>
              </select>
              {getError('status')}
            </div>
            <div>
              <label className="block text-sm font-medium">EMPLOYEE TYPE</label>
              <select 
                id="employee_type"
                value={data.employee_type} 
                onChange={e => setData('employee_type', e.target.value)} 
                className={`w-full border-b ${errors.employee_type ? 'border-red-500' : 'border-black'}`}
                disabled={isView}
              >
                <option value="Teaching">Teaching</option>
                <option value="Non-Teaching">Non-Teaching</option>
              </select>
              {getError('employee_type')}
            </div>
          </div>
          
          <div className="mb-4">
            <h3 className="font-medium">19. RESIDENTIAL ADDRESS</h3>
            <div className="flex items-center mb-2">
              <input 
                type="checkbox" 
                id="sameAsResidential"
                onChange={handleSameAsResidential}
                className="mr-2"
                disabled={isView}
              />
              <label htmlFor="sameAsResidential" className="text-sm">
                Same as Permanent Address
              </label>
            </div>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">House/Block/Lot No.</label>
                <input 
                  id="res_house_no"
                  value={data.res_house_no} 
                  onChange={e => setData('res_house_no', e.target.value)} 
                  className={`w-full border-b ${errors.res_house_no ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('res_house_no')}
              </div>
              <div>
                <label className="block text-xs">Street</label>
                <input 
                  id="res_street"
                  value={data.res_street} 
                  onChange={e => setData('res_street', e.target.value)} 
                  className={`w-full border-b ${errors.res_street ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('res_street')}
              </div>
              <div>
                <label className="block text-xs">Subdivision/Village</label>
                <input 
                  id="res_subdivision"
                  value={data.res_subdivision} 
                  onChange={e => setData('res_subdivision', e.target.value)} 
                  className={`w-full border-b ${errors.res_subdivision ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('res_subdivision')}
              </div>
            </div>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">Barangay</label>
                <input 
                  id="res_barangay"
                  value={data.res_barangay} 
                  onChange={e => setData('res_barangay', e.target.value)} 
                  className={`w-full border-b ${errors.res_barangay ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('res_barangay')}
              </div>
              <div>
                <label className="block text-xs">City/Municipality</label>
                <input 
                  id="res_city"
                  value={data.res_city} 
                  onChange={e => setData('res_city', e.target.value)} 
                  className={`w-full border-b ${errors.res_city ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('res_city')}
              </div>
              <div>
                <label className="block text-xs">Province</label>
                <input 
                  id="res_province"
                  value={data.res_province} 
                  onChange={e => setData('res_province', e.target.value)} 
                  className={`w-full border-b ${errors.res_province ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('res_province')}
              </div>
            </div>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">ZIP CODE</label>
                <input 
                  id="res_zip_code"
                  value={data.res_zip_code} 
                  onChange={e => setData('res_zip_code', e.target.value)} 
                  className={`w-full border-b ${errors.res_zip_code ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('res_zip_code')}
              </div>
            </div>
          </div>
          
          <div className="mb-4">
            <h3 className="font-medium">20. PERMANENT ADDRESS</h3>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">House/Block/Lot No.</label>
                <input 
                  id="perm_house_no"
                  value={data.perm_house_no} 
                  onChange={e => setData('perm_house_no', e.target.value)} 
                  className={`w-full border-b ${errors.perm_house_no ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('perm_house_no')}
              </div>
              <div>
                <label className="block text-xs">Street</label>
                <input 
                  id="perm_street"
                  value={data.perm_street} 
                  onChange={e => setData('perm_street', e.target.value)} 
                  className={`w-full border-b ${errors.perm_street ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('perm_street')}
              </div>
              <div>
                <label className="block text-xs">Subdivision/Village</label>
                <input 
                  id="perm_subdivision"
                  value={data.perm_subdivision} 
                  onChange={e => setData('perm_subdivision', e.target.value)} 
                  className={`w-full border-b ${errors.perm_subdivision ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('perm_subdivision')}
              </div>
            </div>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">Barangay</label>
                <input 
                  id="perm_barangay"
                  value={data.perm_barangay} 
                  onChange={e => setData('perm_barangay', e.target.value)} 
                  className={`w-full border-b ${errors.perm_barangay ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('perm_barangay')}
              </div>
              <div>
                <label className="block text-xs">City/Municipality</label>
                <input 
                  id="perm_city"
                  value={data.perm_city} 
                  onChange={e => setData('perm_city', e.target.value)} 
                  className={`w-full border-b ${errors.perm_city ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('perm_city')}
              </div>
              <div>
                <label className="block text-xs">Province</label>
                <input 
                  id="perm_province"
                  value={data.perm_province} 
                  onChange={e => setData('perm_province', e.target.value)} 
                  className={`w-full border-b ${errors.perm_province ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('perm_province')}
              </div>
            </div>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">ZIP CODE</label>
                <input 
                  id="perm_zip_code"
                  value={data.perm_zip_code} 
                  onChange={e => setData('perm_zip_code', e.target.value)} 
                  className={`w-full border-b ${errors.perm_zip_code ? 'border-red-500' : 'border-black'}`} 
                  readOnly={isView}
                />
                {getError('perm_zip_code')}
              </div>
            </div>
          </div>
          
          <div className="grid grid-cols-3 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">21. TELEPHONE NO.</label>
              <input 
                id="telephone_no"
                value={data.telephone_no} 
                onChange={e => setData('telephone_no', e.target.value)} 
                className={`w-full border-b ${errors.telephone_no ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('telephone_no')}
            </div>
            <div>
              <label className="block text-sm font-medium">22. MOBILE NO.</label>
              <input 
                id="mobile_no"
                value={data.mobile_no} 
                onChange={e => setData('mobile_no', e.target.value)} 
                className={`w-full border-b ${errors.mobile_no ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('mobile_no')}
            </div>
            <div>
              <label className="block text-sm font-medium">23. E-MAIL ADDRESS</label>
              <input 
                id="email_address"
                value={data.email_address} 
                onChange={e => setData('email_address', e.target.value)} 
                className={`w-full border-b ${errors.email_address ? 'border-red-500' : 'border-black'}`} 
                readOnly={isView}
              />
              {getError('email_address')}
            </div>
          </div>

          <div className="grid grid-cols-3 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">24. GOVERNMENT ISSUED ID</label>
              <input 
                id="government_issued_id"
                value={data.government_issued_id} 
                onChange={e => setData('government_issued_id', e.target.value)} 
                className={`w-full border-b ${errors.government_issued_id ? 'border-red-500' : 'border-black'}`} 
                placeholder="ID Type"
                readOnly={isView}
              />
              {getError('government_issued_id')}
            </div>

            <div>
              <label className="block text-sm font-medium">ID NUMBER</label>
              <input 
                id="id_number"
                value={data.id_number} 
                onChange={e => setData('id_number', e.target.value)} 
                className={`w-full border-b ${errors.id_number ? 'border-red-500' : 'border-black'}`} 
                placeholder="ID Number"
                readOnly={isView}
              />
              {getError('id_number')}
            </div>
            <div>
              <label className="block text-sm font-medium">DATE/PLACE OF ISSUE</label>
              <input 
                id="id_date_issued"
                type="date"
                value={data.id_date_issued} 
                onChange={e => setData('id_date_issued', e.target.value)} 
                className={`w-full border-b ${errors.id_date_issued ? 'border-red-500' : 'border-black'}`} 
                placeholder="Date/Place"
                readOnly={isView}
              />
              {getError('id_date_issued')}
            </div>
          </div>

          <div className="grid grid-cols-3 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">INDIGENOUS GROUP</label>
              <input 
                id="indigenous_group"
                value={data.indigenous_group} 
                onChange={e => setData('indigenous_group', e.target.value)} 
                className={`w-full border-b ${errors.indigenous_group ? 'border-red-500' : 'border-black'}`} 
                placeholder="If applicable"
                readOnly={isView}
              />
              {getError('indigenous_group')}
            </div>
            <div>
              <label className="block text-sm font-medium">PWD ID NO.</label>
              <input 
                id="pwd_id_no"
                value={data.pwd_id_no} 
                onChange={e => setData('pwd_id_no', e.target.value)} 
                className={`w-full border-b ${errors.pwd_id_no ? 'border-red-500' : 'border-black'}`} 
                placeholder="If applicable"
                readOnly={isView}
              />
              {getError('pwd_id_no')}
            </div>
            <div>
              <label className="block text-sm font-medium">SOLO PARENT ID NO.</label>
              <input 
                id="solo_parent_id_no"
                value={data.solo_parent_id_no} 
                onChange={e => setData('solo_parent_id_no', e.target.value)} 
                className={`w-full border-b ${errors.solo_parent_id_no ? 'border-red-500' : 'border-black'}`} 
                placeholder="If applicable"
                readOnly={isView}
              />
              {getError('solo_parent_id_no')}
            </div>
          </div>
        </div>

        {/* II. FAMILY BACKGROUND */}
        <div className="border border-black p-4 mb-6">
          <h2 className="font-bold mb-4">II. FAMILY BACKGROUND</h2>
          
          <div className="mb-4">
            <h3 className="font-medium">25. SPOUSE'S SURNAME</h3>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">Surname</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Spouse')?.surname || ''} 
                  onChange={e => {
                    if (isView) return;
                    const spouseIdx = data.family_background.findIndex(fb => fb.relation === 'Spouse');
                    if (spouseIdx >= 0) {
                      updateSection('family_background', spouseIdx, 'surname', e.target.value);
                    } else {
                      addRow('family_background', { 
                        relation: 'Spouse', surname: e.target.value, first_name: '', middle_name: '', 
                        name_extension: '', occupation: '', employer: '', business_address: '', telephone_no: '' 
                      });
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">First Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Spouse')?.first_name || ''} 
                  onChange={e => {
                    if (isView) return;
                    const spouseIdx = data.family_background.findIndex(fb => fb.relation === 'Spouse');
                    if (spouseIdx >= 0) {
                      updateSection('family_background', spouseIdx, 'first_name', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Middle Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Spouse')?.middle_name || ''} 
                  onChange={e => {
                    if (isView) return;
                    const spouseIdx = data.family_background.findIndex(fb => fb.relation === 'Spouse');
                    if (spouseIdx >= 0) {
                      updateSection('family_background', spouseIdx, 'middle_name', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">Name Extension (JR., SR)</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Spouse')?.name_extension || ''} 
                  onChange={e => {
                    if (isView) return;
                    const spouseIdx = data.family_background.findIndex(fb => fb.relation === 'Spouse');
                    if (spouseIdx >= 0) {
                      updateSection('family_background', spouseIdx, 'name_extension', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Occupation</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Spouse')?.occupation || ''} 
                  onChange={e => {
                    if (isView) return;
                    const spouseIdx = data.family_background.findIndex(fb => fb.relation === 'Spouse');
                    if (spouseIdx >= 0) {
                      updateSection('family_background', spouseIdx, 'occupation', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Employer/Business Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Spouse')?.employer || ''} 
                  onChange={e => {
                    if (isView) return;
                    const spouseIdx = data.family_background.findIndex(fb => fb.relation === 'Spouse');
                    if (spouseIdx >= 0) {
                      updateSection('family_background', spouseIdx, 'employer', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
            <div className="grid grid-cols-2 gap-4 mb-2">
              <div>
                <label className="block text-xs">Business Address</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Spouse')?.business_address || ''} 
                  onChange={e => {
                    if (isView) return;
                    const spouseIdx = data.family_background.findIndex(fb => fb.relation === 'Spouse');
                    if (spouseIdx >= 0) {
                      updateSection('family_background', spouseIdx, 'business_address', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Telephone No.</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Spouse')?.telephone_no || ''} 
                  onChange={e => {
                    if (isView) return;
                    const spouseIdx = data.family_background.findIndex(fb => fb.relation === 'Spouse');
                    if (spouseIdx >= 0) {
                      updateSection('family_background', spouseIdx, 'telephone_no', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
          </div>
          
          <div className="mb-4">
            <h3 className="font-medium">26. NAME of CHILDREN (Write full name and list all)</h3>
            <div className="grid grid-cols-2 gap-4 mb-2">
              <div>
                <label className="block text-xs">Full Name</label>
                {data.children.map((child, idx) => (
                  <div key={idx} className="flex items-center gap-2 mb-2">
                    <input 
                      value={child.full_name} 
                      onChange={e => updateSection('children', idx, 'full_name', e.target.value)} 
                      className="flex-1 border-b border-black" 
                      readOnly={isView}
                    />
                    {!isView && (
                      <button 
                        type="button" 
                        onClick={() => removeRow('children', idx)} 
                        className="text-red-500 text-xs"
                      >
                        Remove
                      </button>
                    )}
                  </div>
                ))}
                {!isView && (
                  <button 
                    type="button" 
                    onClick={() => addRow('children', { full_name: '', birth_date: '' })} 
                    className="text-blue-600 text-xs"
                  >
                    + Add Child
                  </button>
                )}
              </div>
              <div>
                <label className="block text-xs">Date of Birth (mm/dd/yyyy)</label>
                {data.children.map((child, idx) => (
                  <input 
                    key={idx} 
                    type="date" 
                    value={child.birth_date} 
                    onChange={e => updateSection('children', idx, 'birth_date', e.target.value)} 
                    className="w-full border-b border-black mb-2" 
                    readOnly={isView}
                  />
                ))}
              </div>
            </div>
          </div>
          
          <div className="mb-4">
            <h3 className="font-medium">27. FATHER'S SURNAME</h3>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">Surname</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Father')?.surname || ''} 
                  onChange={e => {
                    if (isView) return;
                    const fatherIdx = data.family_background.findIndex(fb => fb.relation === 'Father');
                    if (fatherIdx >= 0) {
                      updateSection('family_background', fatherIdx, 'surname', e.target.value);
                    } else {
                      addRow('family_background', { 
                        relation: 'Father', surname: e.target.value, first_name: '', middle_name: '', 
                        name_extension: '', occupation: '', employer: '', business_address: '', telephone_no: '' 
                      });
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">First Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Father')?.first_name || ''} 
                  onChange={e => {
                    if (isView) return;
                    const fatherIdx = data.family_background.findIndex(fb => fb.relation === 'Father');
                    if (fatherIdx >= 0) {
                      updateSection('family_background', fatherIdx, 'first_name', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Middle Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Father')?.middle_name || ''} 
                  onChange={e => {
                    if (isView) return;
                    const fatherIdx = data.family_background.findIndex(fb => fb.relation === 'Father');
                    if (fatherIdx >= 0) {
                      updateSection('family_background', fatherIdx, 'middle_name', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">Name Extension (JR., SR)</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Father')?.name_extension || ''} 
                  onChange={e => {
                    if (isView) return;
                    const fatherIdx = data.family_background.findIndex(fb => fb.relation === 'Father');
                    if (fatherIdx >= 0) {
                      updateSection('family_background', fatherIdx, 'name_extension', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Occupation</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Father')?.occupation || ''} 
                  onChange={e => {
                    if (isView) return;
                    const fatherIdx = data.family_background.findIndex(fb => fb.relation === 'Father');
                    if (fatherIdx >= 0) {
                      updateSection('family_background', fatherIdx, 'occupation', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Employer/Business Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Father')?.employer || ''} 
                  onChange={e => {
                    if (isView) return;
                    const fatherIdx = data.family_background.findIndex(fb => fb.relation === 'Father');
                    if (fatherIdx >= 0) {
                      updateSection('family_background', fatherIdx, 'employer', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
            <div className="grid grid-cols-2 gap-4 mb-2">
              <div>
                <label className="block text-xs">Business Address</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Father')?.business_address || ''} 
                  onChange={e => {
                    if (isView) return;
                    const fatherIdx = data.family_background.findIndex(fb => fb.relation === 'Father');
                    if (fatherIdx >= 0) {
                      updateSection('family_background', fatherIdx, 'business_address', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Telephone No.</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Father')?.telephone_no || ''} 
                  onChange={e => {
                    if (isView) return;
                    const fatherIdx = data.family_background.findIndex(fb => fb.relation === 'Father');
                    if (fatherIdx >= 0) {
                      updateSection('family_background', fatherIdx, 'telephone_no', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
          </div>
          
          <div className="mb-4">
            <h3 className="font-medium">28. MOTHER'S MAIDEN NAME</h3>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">Surname</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Mother')?.surname || ''} 
                  onChange={e => {
                    if (isView) return;
                    const motherIdx = data.family_background.findIndex(fb => fb.relation === 'Mother');
                    if (motherIdx >= 0) {
                      updateSection('family_background', motherIdx, 'surname', e.target.value);
                    } else {
                      addRow('family_background', { 
                        relation: 'Mother', surname: e.target.value, first_name: '', middle_name: '', 
                        name_extension: '', occupation: '', employer: '', business_address: '', telephone_no: '' 
                      });
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">First Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Mother')?.first_name || ''} 
                  onChange={e => {
                    if (isView) return;
                    const motherIdx = data.family_background.findIndex(fb => fb.relation === 'Mother');
                    if (motherIdx >= 0) {
                      updateSection('family_background', motherIdx, 'first_name', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Middle Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Mother')?.middle_name || ''} 
                  onChange={e => {
                    if (isView) return;
                    const motherIdx = data.family_background.findIndex(fb => fb.relation === 'Mother');
                    if (motherIdx >= 0) {
                      updateSection('family_background', motherIdx, 'middle_name', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
            <div className="grid grid-cols-3 gap-4 mb-2">
              <div>
                <label className="block text-xs">Occupation</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Mother')?.occupation || ''} 
                  onChange={e => {
                    if (isView) return;
                    const motherIdx = data.family_background.findIndex(fb => fb.relation === 'Mother');
                    if (motherIdx >= 0) {
                      updateSection('family_background', motherIdx, 'occupation', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Employer/Business Name</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Mother')?.employer || ''} 
                  onChange={e => {
                    if (isView) return;
                    const motherIdx = data.family_background.findIndex(fb => fb.relation === 'Mother');
                    if (motherIdx >= 0) {
                      updateSection('family_background', motherIdx, 'employer', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
              <div>
                <label className="block text-xs">Business Address</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Mother')?.business_address || ''} 
                  onChange={e => {
                    if (isView) return;
                    const motherIdx = data.family_background.findIndex(fb => fb.relation === 'Mother');
                    if (motherIdx >= 0) {
                      updateSection('family_background', motherIdx, 'business_address', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
            <div className="grid grid-cols-1 gap-4 mb-2">
              <div>
                <label className="block text-xs">Telephone No.</label>
                <input 
                  value={data.family_background.find(fb => fb.relation === 'Mother')?.telephone_no || ''} 
                  onChange={e => {
                    if (isView) return;
                    const motherIdx = data.family_background.findIndex(fb => fb.relation === 'Mother');
                    if (motherIdx >= 0) {
                      updateSection('family_background', motherIdx, 'telephone_no', e.target.value);
                    }
                  }} 
                  className="w-full border-b border-black" 
                  readOnly={isView}
                />
              </div>
            </div>
          </div>
        </div>

        {/* III. EDUCATIONAL BACKGROUND */}
        <div className="border border-black p-4 mb-6">
          <h2 className="font-bold mb-4">III. EDUCATIONAL BACKGROUND</h2>
          
          <div className="overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr>
                  <th className="border border-black p-1 text-sm">LEVEL</th>
                  <th className="border border-black p-1 text-sm">NAME OF SCHOOL (Write in full)</th>
                  <th className="border border-black p-1 text-sm">BASIC EDUCATION/DEGREE/COURSE (Write in full)</th>
                  <th className="border border-black p-1 text-sm">PERIOD OF ATTENDANCE</th>
                  <th className="border border-black p-1 text-sm">HIGHEST LEVEL/UNITS EARNED (if not graduated)</th>
                  <th className="border border-black p-1 text-sm">YEAR GRADUATED</th>
                  <th className="border border-black p-1 text-sm">SCHOLARSHIP/ACADEMIC HONORS RECEIVED</th>
                  <th className="border border-black p-1 text-sm">ACTION</th>
                </tr>
              </thead>
              <tbody>
                {data.educational_background.map((edu, idx) => (
                  <tr key={idx}>
                    <td className="border border-black p-1">
                      <select 
                        value={edu.level} 
                        onChange={e => updateSection('educational_background', idx, 'level', e.target.value)} 
                        className="w-full border-none"
                        disabled={isView}
                      >
                        <option value="Elementary">Elementary</option>
                        <option value="Secondary">Secondary</option>
                        <option value="Vocational">Vocational/Trade Course</option>
                        <option value="College">College</option>
                        <option value="Graduate Studies">Graduate Studies</option>
                      </select>
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={edu.school_name} 
                        onChange={e => updateSection('educational_background', idx, 'school_name', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={edu.degree_course} 
                        onChange={e => updateSection('educational_background', idx, 'degree_course', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <div className="grid grid-cols-2 gap-1">
                        <input 
                          type="date" 
                          value={edu.period_from} 
                          onChange={e => updateSection('educational_background', idx, 'period_from', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                        <input 
                          type="date" 
                          value={edu.period_to} 
                          onChange={e => updateSection('educational_background', idx, 'period_to', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                      </div>
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={edu.highest_level_units} 
                        onChange={e => updateSection('educational_background', idx, 'highest_level_units', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={edu.year_graduated} 
                        onChange={e => updateSection('educational_background', idx, 'year_graduated', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={edu.honors_received} 
                        onChange={e => updateSection('educational_background', idx, 'honors_received', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      {!isView && (
                        <button 
                          type="button" 
                          onClick={() => removeRow('educational_background', idx)} 
                          className="text-red-500 text-xs"
                        >
                          Remove
                        </button>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {!isView && (
              <button 
                type="button" 
                onClick={() => addRow('educational_background', {
                  level: 'Elementary', school_name: '', degree_course: '', period_from: '', period_to: '', 
                  highest_level_units: '', year_graduated: '', honors_received: ''
                })} 
                className="text-blue-600 text-xs mt-2"
              >
                + Add Education
              </button>
            )}
          </div>
        </div>

        {/* IV. CIVIL SERVICE ELIGIBILITY */}
        <div className="border border-black p-4 mb-6">
          <h2 className="font-bold mb-4">IV. CIVIL SERVICE ELIGIBILITY</h2>
          
          <div className="overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr>
                  <th className="border border-black p-1 text-sm">CAREER SERVICE/RA 1080 (BOARD/BAR)/CES/CSEE</th>
                  <th className="border border-black p-1 text-sm">RATING</th>
                  <th className="border border-black p-1 text-sm">DATE OF EXAMINATION</th>
                  <th className="border border-black p-1 text-sm">PLACE OF EXAMINATION</th>
                  <th className="border border-black p-1 text-sm">LICENSE NUMBER</th>
                  <th className="border border-black p-1 text-sm">VALIDITY</th>
                  <th className="border border-black p-1 text-sm">ACTION</th>
                </tr>
              </thead>
              <tbody>
                {data.civil_service_eligibility.map((elig, idx) => (
                  <tr key={idx}>
                    <td className="border border-black p-1">
                      <input 
                        value={elig.eligibility} 
                        onChange={e => updateSection('civil_service_eligibility', idx, 'eligibility', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={elig.rating} 
                        onChange={e => updateSection('civil_service_eligibility', idx, 'rating', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        type="date" 
                        value={elig.exam_date} 
                        onChange={e => updateSection('civil_service_eligibility', idx, 'exam_date', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={elig.exam_place} 
                        onChange={e => updateSection('civil_service_eligibility', idx, 'exam_place', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={elig.license_no} 
                        onChange={e => updateSection('civil_service_eligibility', idx, 'license_no', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        type="date" 
                        value={elig.license_validity} 
                        onChange={e => updateSection('civil_service_eligibility', idx, 'license_validity', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      {!isView && (
                        <button 
                          type="button" 
                          onClick={() => removeRow('civil_service_eligibility', idx)} 
                          className="text-red-500 text-xs"
                        >
                          Remove
                        </button>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {!isView && (
              <button 
                type="button" 
                onClick={() => addRow('civil_service_eligibility', {
                  eligibility: '', rating: '', exam_date: '', exam_place: '', license_no: '', license_validity: ''
                })} 
                className="text-blue-600 text-xs mt-2"
              >
                + Add Eligibility
              </button>
            )}
          </div>
        </div>

        {/* V. WORK EXPERIENCE */}
        <div className="border border-black p-4 mb-6">
          <h2 className="font-bold mb-4">V. WORK EXPERIENCE</h2>
          <p className="text-xs italic mb-4">
            (Include private employment. Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet.
          </p>
          
          <div className="overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr>
                  <th className="border border-black p-1 text-sm">INCLUSIVE DATES</th>
                  <th className="border border-black p-1 text-sm">POSITION TITLE</th>
                  <th className="border border-black p-1 text-sm">COMPANY/AGENCY</th>
                  <th className="border border-black p-1 text-sm">COMPANY ADDRESS</th>
                  <th className="border border-black p-1 text-sm">MONTHLY SALARY</th>
                  <th className="border border-black p-1 text-sm">SALARY GRADE/STEP</th>
                  <th className="border border-black p-1 text-sm">APPOINTMENT STATUS</th>
                  <th className="border border-black p-1 text-sm">GOV'T SERVICE</th>
                  <th className="border border-black p-1 text-sm">ACTION</th>
                </tr>
              </thead>
              <tbody>
                {data.work_experience.map((work, idx) => (
                  <tr key={idx}>
                    <td className="border border-black p-1">
                      <div className="grid grid-cols-2 gap-1">
                        <input 
                          type="date" 
                          value={work.date_from} 
                          onChange={e => updateSection('work_experience', idx, 'date_from', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                        <input 
                          type="date" 
                          value={work.date_to} 
                          onChange={e => updateSection('work_experience', idx, 'date_to', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                      </div>
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={work.position_title} 
                        onChange={e => updateSection('work_experience', idx, 'position_title', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={work.company_name} 
                        onChange={e => updateSection('work_experience', idx, 'company_name', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={work.company_address} 
                        onChange={e => updateSection('work_experience', idx, 'company_address', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        type="number"
                        step="0.01"
                        value={work.monthly_salary} 
                        onChange={e => updateSection('work_experience', idx, 'monthly_salary', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={work.salary_grade_step} 
                        onChange={e => updateSection('work_experience', idx, 'salary_grade_step', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={work.status_of_appointment} 
                        onChange={e => updateSection('work_experience', idx, 'status_of_appointment', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <select 
                        value={work.is_gov_service ? 'Y' : 'N'} 
                        onChange={e => updateSection('work_experience', idx, 'is_gov_service', e.target.value === 'Y')} 
                        className="w-full border-none"
                        disabled={isView}
                      >
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                      </select>
                    </td>
                    <td className="border border-black p-1">
                      {!isView && (
                        <button 
                          type="button" 
                          onClick={() => removeRow('work_experience', idx)} 
                          className="text-red-500 text-xs"
                        >
                          Remove
                        </button>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {!isView && (
              <button 
                type="button" 
                onClick={() => addRow('work_experience', {
                  position_title: '', company_name: '', company_address: '', date_from: '', date_to: '', 
                  monthly_salary: '', salary_grade_step: '', status_of_appointment: '', is_gov_service: false
                })} 
                className="text-blue-600 text-xs mt-2"
              >
                + Add Work Experience
              </button>
            )}
          </div>
        </div>

        {/* VI. VOLUNTARY WORK */}
        <div className="border border-black p-4 mb-6">
          <h2 className="font-bold mb-4">VI. VOLUNTARY WORK</h2>
          
          <div className="overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr>
                  <th className="border border-black p-1 text-sm">ORGANIZATION NAME</th>
                  <th className="border border-black p-1 text-sm">ORGANIZATION ADDRESS</th>
                  <th className="border border-black p-1 text-sm">INCLUSIVE DATES</th>
                  <th className="border border-black p-1 text-sm">HOURS</th>
                  <th className="border border-black p-1 text-sm">POSITION/NATURE OF WORK</th>
                  <th className="border border-black p-1 text-sm">ACTION</th>
                </tr>
              </thead>
              <tbody>
                {data.voluntary_work.map((vw, idx) => (
                  <tr key={idx}>
                    <td className="border border-black p-1">
                      <input 
                        value={vw.organization_name} 
                        onChange={e => updateSection('voluntary_work', idx, 'organization_name', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={vw.organization_address} 
                        onChange={e => updateSection('voluntary_work', idx, 'organization_address', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <div className="grid grid-cols-2 gap-1">
                        <input 
                          type="date" 
                          value={vw.date_from} 
                          onChange={e => updateSection('voluntary_work', idx, 'date_from', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                        <input 
                          type="date" 
                          value={vw.date_to} 
                          onChange={e => updateSection('voluntary_work', idx, 'date_to', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                      </div>
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        type="number"
                        value={vw.hours_rendered} 
                        onChange={e => updateSection('voluntary_work', idx, 'hours_rendered', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={vw.position_or_nature} 
                        onChange={e => updateSection('voluntary_work', idx, 'position_or_nature', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      {!isView && (
                        <button 
                          type="button" 
                          onClick={() => removeRow('voluntary_work', idx)} 
                          className="text-red-500 text-xs"
                        >
                          Remove
                        </button>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {!isView && (
              <button 
                type="button" 
                onClick={() => addRow('voluntary_work', {
                  organization_name: '', organization_address: '', date_from: '', date_to: '', 
                  hours_rendered: '', position_or_nature: ''
                })} 
                className="text-blue-600 text-xs mt-2"
              >
                + Add Voluntary Work
              </button>
            )}
          </div>
        </div>

        {/* VII. LEARNING AND DEVELOPMENT */}
        <div className="border border-black p-4 mb-6">
          <h2 className="font-bold mb-4">VII. LEARNING AND DEVELOPMENT</h2>
          
          <div className="overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr>
                  <th className="border border-black p-1 text-sm">TITLE</th>
                  <th className="border border-black p-1 text-sm">INCLUSIVE DATES</th>
                  <th className="border border-black p-1 text-sm">HOURS</th>
                  <th className="border border-black p-1 text-sm">TYPE</th>
                  <th className="border border-black p-1 text-sm">CONDUCTED BY</th>
                  <th className="border border-black p-1 text-sm">ACTION</th>
                </tr>
              </thead>
              <tbody>
                {data.learning_development.map((ld, idx) => (
                  <tr key={idx}>
                    <td className="border border-black p-1">
                      <input 
                        value={ld.title} 
                        onChange={e => updateSection('learning_development', idx, 'title', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <div className="grid grid-cols-2 gap-1">
                        <input 
                          type="date" 
                          value={ld.date_from} 
                          onChange={e => updateSection('learning_development', idx, 'date_from', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                        <input 
                          type="date" 
                          value={ld.date_to} 
                          onChange={e => updateSection('learning_development', idx, 'date_to', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                      </div>
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        type="number"
                        value={ld.hours} 
                        onChange={e => updateSection('learning_development', idx, 'hours', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      <select 
                        value={ld.type_of_ld} 
                        onChange={e => updateSection('learning_development', idx, 'type_of_ld', e.target.value)} 
                        className="w-full border-none"
                        disabled={isView}
                      >
                        <option value="Managerial">Managerial</option>
                        <option value="Supervisory">Supervisory</option>
                        <option value="Technical">Technical</option>
                        <option value="Foundation">Foundation</option>
                        <option value="Others">Others</option>
                      </select>
                    </td>
                    <td className="border border-black p-1">
                      <input 
                        value={ld.conducted_by} 
                        onChange={e => updateSection('learning_development', idx, 'conducted_by', e.target.value)} 
                        className="w-full border-none" 
                        readOnly={isView}
                      />
                    </td>
                    <td className="border border-black p-1">
                      {!isView && (
                        <button 
                          type="button" 
                          onClick={() => removeRow('learning_development', idx)} 
                          className="text-red-500 text-xs"
                        >
                          Remove
                        </button>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
            {!isView && (
              <button 
                type="button" 
                onClick={() => addRow('learning_development', {
                  title: '', date_from: '', date_to: '', hours: '', type_of_ld: 'Technical', conducted_by: ''
                })} 
                className="text-blue-600 text-xs mt-2"
              >
                + Add Training Program
              </button>
            )}
          </div>
        </div>

        {/* VIII. OTHER INFORMATION */}
        <div className="border border-black p-4 mb-6">
          <h2 className="font-bold mb-4">VIII. OTHER INFORMATION</h2>
          
          <div className="grid grid-cols-3 gap-4 mb-4">
            <div>
              <label className="block text-sm font-medium">31. SPECIAL SKILLS and HOBBIES</label>
              <textarea 
                value={data.other_information.skill_or_hobby} 
                onChange={e => setData('other_information', { ...data.other_information, skill_or_hobby: e.target.value })} 
                className="w-full border border-black p-1" 
                rows={3}
                readOnly={isView}
              />
            </div>
            <div>
              <label className="block text-sm font-medium">32. NON-ACADEMIC DISTINCTIONS</label>
              <textarea 
                value={data.other_information.non_academic_distinctions} 
                onChange={e => setData('other_information', { ...data.other_information, non_academic_distinctions: e.target.value })} 
                className="w-full border border-black p-1" 
                rows={3}
                readOnly={isView}
              />
            </div>
            <div>
              <label className="block text-sm font-medium">33. MEMBERSHIPS</label>
              <textarea 
                value={data.other_information.memberships} 
                onChange={e => setData('other_information', { ...data.other_information, memberships: e.target.value })} 
                className="w-full border border-black p-1" 
                rows={3}
                readOnly={isView}
              />
            </div>
          </div>
          
          {/* Questionnaire */}
          <div className="space-y-4">
            {data.questionnaire.map((q, idx) => (
              <div key={idx}>
                <label className="block text-sm font-medium">
                  {idx === 0 && '34. Are you related by consanguinity or affinity to the appointing or recommending authority?'}
                  {idx === 1 && '35. a. Have you ever been found guilty of any administrative offense?'}
                  {idx === 2 && '35. b. Have you been criminally charged before any court?'}
                  {idx === 3 && '36. Have you ever been convicted of any crime or violation?'}
                  {idx === 4 && '37. Have you ever been separated from the service?'}
                  {idx === 5 && '38. a. Have you ever been a candidate in a national or local election?'}
                  {idx === 6 && '38. b. Have you resigned from the government service during the election period?'}
                  {idx === 7 && '39. Have you acquired the status of an immigrant or permanent resident of another country?'}
                </label>
                <div className="flex items-center gap-4 mt-2">
                  <label className="flex items-center gap-1">
                    <input 
                      type="radio" 
                      checked={q.answer === true} 
                      onChange={() => !isView && updateSection('questionnaire', idx, 'answer', true)} 
                      disabled={isView}
                    />
                    Yes
                  </label>
                  <label className="flex items-center gap-1">
                    <input 
                      type="radio" 
                      checked={q.answer === false} 
                      onChange={() => !isView && updateSection('questionnaire', idx, 'answer', false)} 
                      disabled={isView}
                    />
                    No
                  </label>
                  {q.answer && (
                    <input 
                      value={q.details} 
                      onChange={e => updateSection('questionnaire', idx, 'details', e.target.value)} 
                      placeholder="If YES, give details" 
                      className="flex-1 border-b border-black" 
                      readOnly={isView}
                    />
                  )}
                </div>
              </div>
            ))}
          </div>
          
          {/* 40. Special Categories */}
          <div className="mt-6">
            <h3 className="font-medium">40. Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:</h3>
            
            <div className="grid grid-cols-3 gap-4 mt-4">
              <div>
                <label className="block text-sm font-medium">a. Are you a member of any indigenous group?</label>
                <input 
                  value={data.indigenous_group} 
                  onChange={e => setData('indigenous_group', e.target.value)} 
                  className="w-full border-b border-black" 
                  placeholder="If YES, specify group"
                  readOnly={isView}
                />
              </div>
              
              <div>
                <label className="block text-sm font-medium">b. Are you a person with disability?</label>
                <input 
                  value={data.pwd_id_no} 
                  onChange={e => setData('pwd_id_no', e.target.value)} 
                  className="w-full border-b border-black" 
                  placeholder="If YES, specify ID No"
                  readOnly={isView}
                />
              </div>
              
              <div>
                <label className="block text-sm font-medium">c. Are you a solo parent?</label>
                <input 
                  value={data.solo_parent_id_no} 
                  onChange={e => setData('solo_parent_id_no', e.target.value)} 
                  className="w-full border-b border-black" 
                  placeholder="If YES, specify ID No"
                  readOnly={isView}
                />
              </div>
            </div>
          </div>
          
          {/* 41. References */}
          <div className="mt-6">
            <h3 className="font-medium">41. REFERENCES (Person not related by consanguinity or affinity to applicant/appointee)</h3>
            
            <div className="overflow-x-auto mt-4">
              <table className="w-full border-collapse">
                <thead>
                  <tr>
                    <th className="border border-black p-1 text-sm">NAME</th>
                    <th className="border border-black p-1 text-sm">ADDRESS</th>
                    <th className="border border-black p-1 text-sm">TEL. NO.</th>
                    <th className="border border-black p-1 text-sm">ACTION</th>
                  </tr>
                </thead>
                <tbody>
                  {data.references.map((ref, idx) => (
                    <tr key={idx}>
                      <td className="border border-black p-1">
                        <div className="grid grid-cols-3 gap-1">
                          <input 
                            value={ref.first_name} 
                            onChange={e => updateSection('references', idx, 'first_name', e.target.value)} 
                            placeholder="First Name" 
                            className="w-full border-none" 
                            readOnly={isView}
                          />
                          <input 
                            value={ref.middle_initial} 
                            onChange={e => updateSection('references', idx, 'middle_initial', e.target.value)} 
                            placeholder="M.I." 
                            className="w-full border-none" 
                            readOnly={isView}
                          />
                          <input 
                            value={ref.surname} 
                            onChange={e => updateSection('references', idx, 'surname', e.target.value)} 
                            placeholder="Last Name" 
                            className="w-full border-none" 
                            readOnly={isView}
                          />
                        </div>
                      </td>
                      <td className="border border-black p-1">
                        <input 
                          value={ref.address} 
                          onChange={e => updateSection('references', idx, 'address', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                      </td>
                      <td className="border border-black p-1">
                        <input 
                          value={ref.telephone_no} 
                          onChange={e => updateSection('references', idx, 'telephone_no', e.target.value)} 
                          className="w-full border-none" 
                          readOnly={isView}
                        />
                      </td>
                      <td className="border border-black p-1">
                        {!isView && (
                          <button 
                            type="button" 
                            onClick={() => removeRow('references', idx)} 
                            className="text-red-500 text-xs"
                          >
                            Remove
                          </button>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
              {!isView && (
                <button 
                  type="button" 
                  onClick={() => addRow('references', {
                    first_name: '', middle_initial: '', surname: '', address: '', telephone_no: ''
                  })} 
                  className="text-blue-600 text-xs mt-2"
                >
                  + Add Reference
                </button>
              )}
            </div>
          </div>
          
          {/* Declaration */}
          <div className="mt-8">
            <h3 className="font-medium">42. DECLARATION</h3>
            <p className="text-xs mt-2">
              I declare under oath that I have personally accomplished this Personal Data Sheet which is a true, correct and complete statement pursuant to the provisions of pertinent laws, rules and regulations of the Republic of the Philippines. I authorize the agency head/authorized representative to verify/validate the contents stated herein. I agree that any misrepresentation made in this document and its attachments shall cause the filing of administrative/criminal case/s against me.
            </p>
            
            <div className="grid grid-cols-3 gap-4 mt-6">
              <div className="col-span-2">
                <div className="mb-4">
                  <label className="block text-sm font-medium">Government Issued ID (i.e.Passport, GSIS, SSS, PRC, Driver's License, etc.) PLEASE INDICATE ID Number and Date of Issuance</label>
                  <div className="grid grid-cols-2 gap-2 mt-2">
                    <input 
                      value={data.government_issued_id} 
                      onChange={e => setData('government_issued_id', e.target.value)} 
                      placeholder="ID Type" 
                      className="border-b border-black" 
                      readOnly={isView}
                    />
                    <input 
                      value={data.id_number} 
                      onChange={e => setData('id_number', e.target.value)} 
                      placeholder="ID/License/Passport No." 
                      className="border-b border-black" 
                      readOnly={isView}
                    />
                  </div>
                  <div className="grid grid-cols-2 gap-2 mt-2">
                    <input 
                      type="date"
                      value={data.id_date_issued} 
                      onChange={e => setData('id_date_issued', e.target.value)} 
                      placeholder="Date of Issuance" 
                      className="border-b border-black" 
                      readOnly={isView}
                    />
                    <input 
                      value={data.id_place_of_issue} 
                      onChange={e => setData('id_place_of_issue', e.target.value)} 
                      placeholder="Place of Issuance" 
                      className="border-b border-black" 
                      readOnly={isView}
                    />
                  </div>
                </div>
                
                <div className="flex items-center justify-between mt-8">
                  <div className="w-48 h-24 border border-black flex items-center justify-center">
                    <span className="text-xs">Signature (Sign inside the box)</span>
                  </div>
                  <div className="text-center">
                    <input 
                      type="date" 
                      className="border-b border-black text-center" 
                      placeholder="Date Accomplished" 
                      readOnly={isView}
                    />
                  </div>
                  <div className="w-24 h-24 border border-black flex items-center justify-center">
                    <span className="text-xs">Right Thumbmark</span>
                  </div>
                </div>
              </div>
              
              <div className="flex justify-center">
                <div className="w-32 h-40 border border-black flex items-center justify-center">
                  <span className="text-xs">PHOTO</span>
                </div>
              </div>
            </div>
            
            <div className="mt-8">
              <p className="text-xs italic">
                SUBSCRIBED AND SWORN to before me this _______, affiant exhibiting his/her validly issued government ID as indicated above.
              </p>
              
              <div className="mt-12 text-center">
                <div className="border-t border-black w-64 mx-auto pt-1">
                  <p className="text-sm">Person Administering Oath</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {!isView && (
          <div className="text-center mt-8">
            <Button type="submit" disabled={processing}>
              {isEdit ? 'Update Employee' : 'Save Employee'}
            </Button>
          </div>
        )}
      </form>
    </AppLayout>
  );
}