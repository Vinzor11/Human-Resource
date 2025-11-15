import { CirclePlus } from "lucide-react";

export const PositionModalFormConfig = {
    moduleTitle: 'Manage Positions',
    title: 'Create Position',
    description: 'Fill in the details below to create a new position.',
    addButton: {
        id: 'add-position',
        label: 'Add Position',
        className: 'w-fit bg-indigo-700 text-white rounded-lg px-4 py-2 hover:bg-indigo-800 cursor-pointer',
        icon: CirclePlus,
        type: 'button',
        variant: 'default',
        permission: 'create-position',
    },

    fields: [
        {
            id: 'pos-name',
            key: 'pos_name',
            name: 'pos_name',
            label: 'Position Title',
            type: 'text',
            placeholder: 'Enter position title',
            autocomplete: 'position_title',
            tabIndex: 1,
        },
        {
            id: 'pos-code',
            key: 'pos_code',
            name: 'pos_code',
            label: 'Position Code',
            type: 'text',
            placeholder: 'Enter position code',
            autocomplete: 'position_code',
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
            label: 'Save Position',
            variant: 'default',
            className: 'cursor-pointer',
        },
    ],
};
