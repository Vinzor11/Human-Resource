import { CustomModalForm } from '@/components/custom-modal-form';
import { CustomTable } from '@/components/custom-table';
import { CustomToast, toast } from '@/components/custom-toast';
import { DepartmentModalFormConfig } from '@/config/forms/department-modal-form';
import { DepartmentTableConfig } from '@/config/tables/department-table';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Manage Departments', href: '/departments' },
];

interface Department {
  department_id: number;
  faculty_code: string;
  faculty_name: string;
  description?: string;
}

interface LinkProps {
  active: boolean;
  label: string;
  url: string;
}

interface Pagination<T> {
  data: T[];
  links: LinkProps[];
  from: number;
  to: number;
  total: number;
}

interface FlashProps extends Record<string, any> {
  flash?: {
    success?: string;
    error?: string;
  };
}

interface IndexProps {
  departments: Pagination<Department>;
}

export default function DepartmentIndex({ departments }: IndexProps) {
  const { flash } = usePage<{ flash?: { success?: string; error?: string } }>().props;
  const flashMessage = flash?.success || flash?.error;

  const [modalOpen, setModalOpen] = useState(false);
  const [mode, setMode] = useState<'create' | 'view' | 'edit'>('create');
  const [selectedDepartment, setSelectedDepartment] = useState<Department | null>(null);

  const { data, setData, errors, processing, reset, post } = useForm({
    faculty_code: '',
    faculty_name: '',
    description: '',
    _method: 'POST',
  });

  const handleDelete = (routePath: string) => {
    if (confirm('Are you sure you want to delete this department?')) {
      router.delete(routePath, {
        preserveScroll: true,
        onSuccess: (res: { props: FlashProps }) => {
          const successMessage = res.props.flash?.success;
          if (successMessage) toast.success(successMessage);
          closeModal();
        },
        onError: (err) => {
          const errorMessage = err?.message || 'An error occurred.';
          toast.error(errorMessage);
        },
      });
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const method = mode === 'edit' ? 'PUT' : 'POST';
    data._method = method;

    const routePath =
      mode === 'edit' && selectedDepartment
        ? route('departments.update', selectedDepartment.department_id)
        : route('departments.store');

    post(routePath, {
      onSuccess: (res: { props: FlashProps }) => {
        const successMessage = res.props.flash?.success;
        if (successMessage) toast.success(successMessage);
        closeModal();
      },
      onError: (err: any) => {
        toast.error(err?.message || 'Error occurred');
      },
    });
  };

  const openModal = (mode: 'create' | 'view' | 'edit', department?: Department) => {
    setMode(mode);
    if (department) {
      setSelectedDepartment(department);
      setData({
        faculty_code: department.faculty_code,
        faculty_name: department.faculty_name,
        description: department.description || '',
        _method: 'PUT',
      });
    } else {
      reset();
    }
    setModalOpen(true);
  };

  const closeModal = () => {
    setMode('create');
    setSelectedDepartment(null);
    reset();
    setModalOpen(false);
  };

  const handleModalToggle = (open: boolean) => {
    if (!open) closeModal();
    else setModalOpen(true);
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Departments" />
      <CustomToast />

      <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        {/* Modal Form */}
        <div className="ml-auto">
          <CustomModalForm
            addButton={DepartmentModalFormConfig.addButton}
            title={
              mode === 'view'
                ? 'View Department'
                : mode === 'edit'
                ? 'Update Department'
                : DepartmentModalFormConfig.title
            }
            description={DepartmentModalFormConfig.description}
            fields={DepartmentModalFormConfig.fields}
            buttons={DepartmentModalFormConfig.buttons}
            data={data}
            setData={setData}
            errors={errors}
            processing={processing}
            handleSubmit={handleSubmit}
            open={modalOpen}
            onOpenChange={handleModalToggle}
            mode={mode}
          />
        </div>

        {/* Table */}
        <CustomTable
          columns={DepartmentTableConfig.columns}
          actions={DepartmentTableConfig.actions}
          data={departments.data}
          from={departments.from}
          onDelete={handleDelete}
          onView={(item) => openModal('view', item)}
          onEdit={(item) => openModal('edit', item)}
          isModal={true}
          getRowKey={(row) => row.department_id} // ✅ use this in CustomTable to assign `key`
        />
      </div>
    </AppLayout>
  );
}
