export const PositionTableConfig = {
    columns: [
        { label: 'Position Code', key: 'pos_code', className: 'border p-4' },
        { label: 'Position Name', key: 'pos_name', className: 'border p-4' },
        { label: 'Description', key: 'description', className: 'border p-4' },
        { label: 'Actions', key: 'actions', isAction: true, className: 'border p-4' },
    ],
    actions: [
        {
            label: 'View',
            icon: 'Eye',
            className: 'cursor-pointer rounded-lg bg-sky-600 p-2 text-white hover:opacity-90',
            permission: 'view-position',
        },
        {
            label: 'Edit',
            icon: 'Pencil',
            className: 'ms-2 cursor-pointer rounded-lg bg-blue-600 p-2 text-white hover:opacity-90',
            permission: 'edit-position',
        },
        {
            label: 'Delete',
            icon: 'Trash2',
            route: 'positions.destroy',
            className: 'ms-2 cursor-pointer rounded-lg bg-red-600 p-2 text-white hover:opacity-90',
            permission: 'delete-position',
        },
    ]
}
