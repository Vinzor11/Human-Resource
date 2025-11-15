import { Link, usePage } from '@inertiajs/react';
import * as LucidIcons from 'lucide-react';
import { Button } from './ui/button';
import { hasPermission } from '@/utils/authorization';

// Enhanced helper function with better null/undefined handling
const getNestedValue = (obj: any, path: string): any => {
  if (!obj || typeof obj !== 'object') return null;
  
  return path.split('.').reduce((acc, key) => {
    if (acc === null || acc === undefined || typeof acc !== 'object') return null;
    return acc[key] !== undefined ? acc[key] : null;
  }, obj);
};

interface TableColumn {
    label: string;
    key: string;
    isImage?: boolean;
    isAction?: boolean;
    className?: string;
    type?: string;
    displayKey?: string;
    defaultValue?: any;
    format?: (value: any) => string;
    group?: string;
    visible?: boolean;
    alwaysVisible?: boolean;
}

interface ActionConfig {
    label: string;
    icon: keyof typeof LucidIcons;
    route?: string;
    className: string;
    permission?: string;
    confirm?: {
        title: string;
        message: string;
    };
    onClick?: (row: any) => void;
}

interface TableRow {
    [key: string]: any;
    id?: string | number;
}

interface CustomTableProps {
    columns: TableColumn[];
    actions: ActionConfig[];
    data: TableRow[];
    from: number;
    onDelete?: (route: string) => void;
    onView?: (row: TableRow) => void;
    onEdit?: (row: TableRow) => void;
    isModal?: boolean;
}

export const CustomTableWithTextArrays = ({ 
    columns, 
    actions, 
    data, 
    from, 
    onDelete, 
    onView, 
    onEdit, 
    isModal = false 
}: CustomTableProps) => {
    const { auth } = usePage().props as any;
    const permissions = auth?.permissions || [];

    const renderActionButtons = (row: TableRow) => {
        return (
            <div className="flex justify-center gap-1">
                {actions.map((action, index) => {
                    // Skip if permission check fails
                    if (action.permission && !hasPermission(permissions, action.permission)) {
                        return null;
                    }

                    const IconComponent = LucidIcons[action.icon] as React.ElementType;
                    const actionKey = `action-${row.id || 'no-id'}-${index}`;

                    // Handle custom onClick actions first
                    if (action.onClick) {
                        return (
                            <Button
                                key={actionKey}
                                className={action.className}
                                onClick={() => action.onClick?.(row)}
                            >
                                <IconComponent size={18} />
                            </Button>
                        );
                    }

                    // Handle view action
                    if (action.label === 'View') {
                        return (
                            <Button
                                key={actionKey}
                                className={action.className}
                                onClick={() => onView?.(row)}
                            >
                                <IconComponent size={18} />
                            </Button>
                        );
                    }

                    // Handle edit action
                    if (action.label === 'Edit') {
                        return (
                            <Button
                                key={actionKey}
                                className={action.className}
                                onClick={() => onEdit?.(row)}
                            >
                                <IconComponent size={18} />
                            </Button>
                        );
                    }

                    // Handle delete action
                    if (action.label === 'Delete') {
                        return (
                            <Button
                                key={actionKey}
                                className={action.className}
                                onClick={() => {
                                    if (!action.route) return;
                                    if (action.confirm && !confirm(action.confirm.message)) return;
                                    onDelete?.(route(action.route, row.id));
                                }}
                            >
                                <IconComponent size={18} />
                            </Button>
                        );
                    }

                    // Default action with route navigation
                    if (action.route) {
                        return (
                            <Link 
                                key={actionKey}
                                as="button"
                                href={route(action.route, row.id)}
                                className={action.className}
                            >
                                <IconComponent size={18} />
                            </Link>
                        );
                    }

                    return null;
                })}
            </div>
        );
    };

    const renderMultiValuesAsText = (items: any[], column: TableColumn) => {
        if (!items || items.length === 0) {
            return column.defaultValue ?? '-';
        }

        const propertyName = column.displayKey || column.key.split('.')[1];
        const values = items.map((item) => {
            if (!item || typeof item !== 'object') return null;
            
            let displayValue = '-';
            
            if (propertyName) {
                displayValue = getNestedValue(item, propertyName) ?? '-';
            } else if (column.format) {
                displayValue = column.format(item) ?? '-';
            } else {
                displayValue = item.name || item.title || item.full_name || 
                            item.first_name || item.surname || item.label || '-';
            }

            return displayValue;
        }).filter(Boolean);

        return values.join(', ');
    };

    const renderCellValue = (col: TableColumn, cellValue: any, row: TableRow) => {
        // Handle action columns first
        if (col.isAction) {
            return renderActionButtons(row);
        }

        // Handle null/undefined values
        if (cellValue === null || cellValue === undefined) {
            return col.defaultValue ?? '-';
        }

        // Handle image columns
        if (col.isImage) {
            return (
                <div className="flex justify-center">
                    <img 
                        src={cellValue || ''} 
                        alt={row.name || 'Image'} 
                        className="h-16 w-20 rounded-lg object-cover" 
                        onError={(e) => {
                            (e.target as HTMLImageElement).style.display = 'none';
                        }}
                    />
                </div>
            );
        }

        // Handle multi-value relationships
        if (col.type === 'multi-values') {
            const arrayKey = col.key.split('.')[0];
            const itemsArray = Array.isArray(row[arrayKey]) ? row[arrayKey] : [];
            return renderMultiValuesAsText(itemsArray, col);
        }

        // Handle formatted values
        if (col.format) {
            return col.format(cellValue) ?? '-';
        }

        // Default case
        return cellValue ?? col.defaultValue ?? '-';
    };

    return (
        <div className="overflow-hidden rounded-lg border bg-white shadow-sm">
            <table className="w-full table-auto">
                <thead>
                    <tr className="bg-gray-700 text-white">
                        <th className="border p-4">#</th>
                        {columns.map((column) => (
                            <th 
                                key={`header-${column.key}`} 
                                className={`border p-4 ${column.className}`}
                            >
                                {column.label}
                            </th>
                        ))}
                    </tr>
                </thead>

                <tbody>
                    {data.length > 0 ? (
                        data.map((row, rowIndex) => (
                            <tr key={`row-${row.id || rowIndex}`}>
                                <td className="border px-4 py-2 text-center">{from + rowIndex}</td>
                                {columns.map((col) => {
                                    const cellKey = col.type === 'multi-values' 
                                        ? col.key.split('.')[0] 
                                        : col.key;
                                    
                                    const cellValue = getNestedValue(row, cellKey);
                                    return (
                                        <td 
                                            key={`cell-${col.key}-${row.id || rowIndex}`}
                                            className={`border px-4 py-2 ${col.className}`}
                                        >
                                            {renderCellValue(col, cellValue, row)}
                                        </td>
                                    );
                                })}
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td 
                                colSpan={columns.length + 1} 
                                className="text-md py-4 text-center font-bold text-red-600"
                            >
                                No Data Found!
                            </td>
                        </tr>
                    )}
                </tbody>
            </table>
        </div>
    );
};