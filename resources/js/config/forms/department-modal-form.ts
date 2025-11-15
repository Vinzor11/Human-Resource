import { CirclePlus } from "lucide-react";

export const DepartmentModalFormConfig = {
    moduleTitle: 'Manage Departments',
    title: 'Create Department',
    description: 'Fill in the details below to create a new department.',
    addButton: {
        id: 'add-department',
        label: 'Add Department',
        className: 'bg-indigo-700 text-white rounded-lg px-4 py-2 hover:bg-indigo-800 cursor-pointer',
        icon: CirclePlus,
        type: 'button',
        variant: 'default',
        permission: 'create-department',
    },
        fields: [
        {
            id: 'faculty-name',
            key: 'faculty_name',
            name: 'faculty_name', // ✅ change this
            label: 'Faculty Name',
            type: 'text',
            placeholder: 'Enter faculty name',
            autocomplete: 'faculty_name',
            tabIndex: 1,
        },
        {
            id: 'faculty-code',
            key: 'faculty_code',
            name: 'faculty_code', // ✅ change this
            label: 'Faculty Code',
            type: 'text',
            placeholder: 'Enter faculty code',
            autocomplete: 'faculty_code',
            tabIndex: 2,
        },
        {
            id: 'description',
            key: 'description',
            name: 'description',
            label: 'Description',
            type: 'textarea',
            placeholder: 'Optional description',
            tabIndex: 3,
        },
    ],
    buttons: [
        {
            key: 'cancel',
            type: 'button',
            label: 'Cancel',
            variant: 'ghost',
            className: 'cursor-pointer',
        },
        {
            key: 'submit',
            type: 'submit',
            label: 'Save Department',
            variant: 'default',
            className: 'cursor-pointer',
        },
    ],
};
