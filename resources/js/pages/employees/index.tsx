import { CustomTableWithTextArrays } from '@/components/CustomTableWithTextArrays ';
import { CustomToast, toast } from '@/components/custom-toast';
import { EmployeeTableConfig } from '@/config/tables/employee-table';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Logs, UserPlus, Search } from 'lucide-react';
import { useState, useEffect, useCallback, useMemo } from 'react';
import { Dialog, DialogContent, DialogTrigger, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { debounce } from 'lodash';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Manage Employees', href: '/employees' },
];

interface PaginationLink {
  active: boolean;
  label: string;
  url: string | null;
}

interface Department {
  id: number;
  faculty_name: string;
}

interface Position {
  id: number;
  pos_name: string;
}

interface OtherInformation {
  skill_or_hobby?: string;
  non_academic_distinctions?: string;
  memberships?: string;
}

interface Employee {
  id: string;
  surname: string;
  first_name: string;
  middle_name?: string;
  name_extension?: string;
  status: string;
  employee_type: string;
  department: Department;
  position: Position;
  birth_date: string;
  birth_place: string;
  sex: string;
  civil_status: string;
  other_information?: OtherInformation;
}

interface EmployeeData {
  data: Employee[];
  links: PaginationLink[];
  meta?: {
    current_page: number;
    from: number;
    to: number;
    total: number;
    last_page: number;
    per_page: number;
    path: string;
  };
}

interface TableColumn {
  key: string;
  label: string;
  visible: boolean;
  alwaysVisible?: boolean;
}

const MAX_VISIBLE_COLUMNS = 100;
const PER_PAGE_OPTIONS = [5, 10, 20, 50, 100] as const;
const LOCAL_STORAGE_KEY = 'employeeTableVisibleColumns';

export default function Index() {
  const { employees, filters, flash } = usePage<{
    employees: EmployeeData;
    filters?: { search?: string; per_page?: number };
    flash?: { success?: string; error?: string };
  }>().props;

  const [columnModalOpen, setColumnModalOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState(filters?.search || '');
  const [dataVersion, setDataVersion] = useState(0);
  const allColumns = EmployeeTableConfig.columns;

  const [visibleColumns, setVisibleColumns] = useState<string[]>(() => {
    if (typeof window !== 'undefined') {
      const savedColumns = localStorage.getItem(LOCAL_STORAGE_KEY);
      const defaultColumns = allColumns
        .filter(col => col.visible || col.alwaysVisible)
        .map(col => col.key);
      return savedColumns ? JSON.parse(savedColumns) : defaultColumns;
    }
    return allColumns.filter(col => col.visible || col.alwaysVisible).map(col => col.key);
  });

  useEffect(() => {
    if (typeof window !== 'undefined') {
      localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(visibleColumns));
    }
  }, [visibleColumns]);

  // Create a mapping of group to its columns
  const groupToColumns = useMemo(() => {
    return EmployeeTableConfig.columns.reduce((acc, col) => {
      if (col.group && col.group !== 'actions') {
        if (!acc[col.group]) {
          acc[col.group] = [];
        }
        acc[col.group].push(col.key);
      }
      return acc;
    }, {} as Record<string, string[]>);
  }, []);

  // Extract unique groups from the columns
  const columnGroups = useMemo(() => {
    return Array.from(
      new Set(EmployeeTableConfig.columns.map(col => col.group))
    ).filter((group): group is string => !!group && group !== 'actions');
  }, []);

  const normalizedEmployees = employees?.data?.map(employee => ({
    ...employee,
    other_information: {
      skill_or_hobby: '',
      non_academic_distinctions: '',
      memberships: '',
      ...(employee.other_information || {})
    }
  })) || [];

  const filteredColumns = allColumns.filter(col => visibleColumns.includes(col.key) || col.alwaysVisible);

  useEffect(() => {
    if (flash?.success) toast.success(flash.success);
    if (flash?.error) toast.error(flash.error);
  }, [flash]);

  const handleSearch = useCallback(
    debounce((term: string) => {
      router.get(route('employees.index'), 
        { search: term, per_page: filters?.per_page || 10, page: 1 },
        { preserveState: true, replace: true }
      );
    }, 500),
    [filters?.per_page]
  );

  const handlePageChange = (page: number) => {
    setDataVersion(prev => prev + 1);
    router.get(employees?.meta?.path || route('employees.index'), {
      page,
      per_page: employees?.meta?.per_page || 10,
      search: searchTerm
    }, {
      preserveState: true,
      preserveScroll: true,
    });
  };

  const handlePerPageChange = (value: string) => {
    setDataVersion(prev => prev + 1);
    router.get(route('employees.index'), {
      per_page: value,
      page: 1,
      search: searchTerm
    }, {
      preserveState: true,
      preserveScroll: false,
      replace: true
    });
  };

  const toggleColumn = (key: string) => {
    if (allColumns.find(col => col.key === key)?.alwaysVisible) return; // Prevent toggling alwaysVisible columns
    setVisibleColumns(prev => {
      if (prev.includes(key)) {
        return prev.filter(colKey => colKey !== key);
      } else {
        if (prev.length >= MAX_VISIBLE_COLUMNS) {
          toast.error(`You can only show up to ${MAX_VISIBLE_COLUMNS} columns at once`);
          return prev;
        }
        return [...prev, key];
      }
    });
  };

  const toggleGroupColumns = (group: string) => {
    const groupColumns = groupToColumns[group] || [];
    const allVisible = groupColumns.length > 0 && 
      groupColumns.every(col => visibleColumns.includes(col));

    if (allVisible) {
      setVisibleColumns(prev => 
        prev.filter(col => !groupColumns.includes(col))
      );
    } else {
      setVisibleColumns(prev => {
        const newColumns = [...prev];
        groupColumns.forEach(col => {
          if (!newColumns.includes(col) && !allColumns.find(c => c.key === col)?.alwaysVisible) {
            if (newColumns.length >= MAX_VISIBLE_COLUMNS) {
              toast.error(`You can only show up to ${MAX_VISIBLE_COLUMNS} columns at once`);
              return prev;
            }
            newColumns.push(col);
          }
        });
        return newColumns;
      });
    }
  };

  const resetToDefaultColumns = () => {
    const defaultColumns = allColumns
      .filter(col => col.visible || col.alwaysVisible)
      .map(col => col.key);
    setVisibleColumns(defaultColumns);
    toast.success('Columns reset to default');
  };

  const handleDelete = useCallback((route: string) => {
    if (confirm('Are you sure you want to delete this employee?')) {
      router.delete(route, {
        preserveScroll: true,
        onSuccess: () => toast.success('Employee deleted successfully'),
        onError: () => toast.error('Failed to delete employee'),
      });
    }
  }, []);

  const currentPage = employees?.meta?.current_page || 1;
  const lastPage = employees?.meta?.last_page || 1;
  const perPage = employees?.meta?.per_page || 10;
  const from = employees?.meta?.from || 0;
  const to = employees?.meta?.to || 0;
  const total = employees?.meta?.total || 0;

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Employees Management" />
      <CustomToast />

      <div className="flex flex-col gap-4 p-4">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <div className="relative w-full md:w-auto">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <Input
              type="text"
              placeholder="Search employees..."
              className="pl-10 pr-4 py-2 w-full md:w-64"
              value={searchTerm}
              onChange={(e) => {
                setSearchTerm(e.target.value);
                handleSearch(e.target.value);
              }}
            />
          </div>

          <div className="flex flex-col md:flex-row gap-2 w-full md:w-auto">
            <div className="flex items-center gap-2">
              <span className="text-sm text-gray-600 whitespace-nowrap">Rows per page:</span>
              <Select
                value={perPage.toString()}
                onValueChange={handlePerPageChange}
              >
                <SelectTrigger className="w-[80px]">
                  <SelectValue placeholder="Rows" />
                </SelectTrigger>
                <SelectContent>
                  {PER_PAGE_OPTIONS.map(option => (
                    <SelectItem key={option} value={option.toString()}>
                      {option}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <Dialog open={columnModalOpen} onOpenChange={setColumnModalOpen}>
              <DialogTrigger asChild>
                <Button variant="outline">
                  <Logs className="h-4 w-4 mr-2" />
                  Select Columns
                </Button>
              </DialogTrigger>
              <DialogContent className="max-w-2xl max-h-[80vh] overflow-hidden flex flex-col">
                <DialogTitle className="text-lg font-semibold">
                  Select Columns (Max {MAX_VISIBLE_COLUMNS})
                </DialogTitle>
                
                <div className="flex justify-between items-center mb-4">
                  <DialogDescription>
                    Choose which columns to display in the employee table
                  </DialogDescription>
                  <Button 
                    variant="link" 
                    onClick={resetToDefaultColumns}
                    className="text-sm text-blue-600"
                  >
                    Reset to Default
                  </Button>
                </div>
                
                <div className="flex-1 overflow-y-auto">
                  {columnGroups.map(group => {
                    const groupColumns = groupToColumns[group] || [];
                    const allGroupColumnsVisible = groupColumns.length > 0 && 
                      groupColumns.every(key => visibleColumns.includes(key));
                    const someGroupColumnsVisible = groupColumns.length > 0 && 
                      groupColumns.some(key => visibleColumns.includes(key));

                    return (
                      <div key={group} className="mb-6">
                        <div className="flex items-center gap-2 mb-2 p-2 bg-gray-50 rounded-md">
                          <input
                            type="checkbox"
                            checked={allGroupColumnsVisible}
                            ref={(el) => {
                              if (el) {
                                el.indeterminate = someGroupColumnsVisible && !allGroupColumnsVisible;
                              }
                            }}
                            onChange={() => toggleGroupColumns(group)}
                            className="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                          />
                          <span className="font-medium capitalize">
                            {group.replace(/_/g, ' ')}
                          </span>
                        </div>
                        
                        <div className="grid grid-cols-2 gap-3 pl-6">
                          {EmployeeTableConfig.columns
                            .filter(col => col.group === group && !col.alwaysVisible)
                            .map(col => (
                              <label key={col.key} className="flex items-center gap-2 text-sm">
                                <input
                                  type="checkbox"
                                  checked={visibleColumns.includes(col.key)}
                                  onChange={() => toggleColumn(col.key)}
                                  className="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <span>{col.label}</span>
                              </label>
                            ))}
                        </div>
                      </div>
                    );
                  })}
                </div>
              </DialogContent>
            </Dialog>
            <Button 
              onClick={() => router.visit(route('employees.create'))}
              className="bg-indigo-600 hover:bg-indigo-700 text-white"
            >
              <UserPlus className="h-4 w-4 mr-2" />
              Add Employee
            </Button>
          </div>
        </div>

        <div className="overflow-x-auto">
          <CustomTableWithTextArrays
            key={`employee-table-${dataVersion}`}
            columns={filteredColumns}
            actions={EmployeeTableConfig.actions}
            data={normalizedEmployees}
            from={from}
            onDelete={handleDelete}
            onView={(employee) => router.visit(route('employees.show', employee.id))}
            onEdit={(employee) => router.visit(route('employees.edit', employee.id))}
          />
        </div>

        <div className="flex flex-col md:flex-row items-center justify-between gap-4 mt-4">
          <div className="text-sm text-gray-600">
            Showing {from} to {to} of {total} employees
          </div>
          
          <div className="flex flex-wrap gap-1 justify-center">
            <button
              onClick={() => handlePageChange(currentPage - 1)}
              disabled={currentPage === 1}
              className="px-3 py-1 rounded-md bg-white text-gray-700 enabled:hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              &laquo; Previous
            </button>

            {lastPage > 1 && (
              <>
                <button
                  onClick={() => handlePageChange(1)}
                  disabled={currentPage === 1}
                  className={`px-3 py-1 rounded-md min-w-[40px] ${
                    currentPage === 1 
                      ? 'bg-blue-600 text-white' 
                      : 'bg-white text-gray-700 hover:bg-gray-100'
                  }`}
                >
                  1
                </button>

                {currentPage > 3 && <span className="px-2 py-1">...</span>}

                {Array.from({ length: Math.min(5, lastPage - 2) }, (_, i) => {
                  const page = Math.max(2, Math.min(
                    currentPage - 2,
                    lastPage - 4
                  )) + i;
                  if (page >= 2 && page < lastPage) {
                    return (
                      <button
                        key={page}
                        onClick={() => handlePageChange(page)}
                        disabled={currentPage === page}
                        className={`px-3 py-1 rounded-md min-w-[40px] ${
                          currentPage === page 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-white text-gray-700 hover:bg-gray-100'
                        }`}
                      >
                        {page}
                      </button>
                    );
                  }
                  return null;
                })}

                {currentPage < lastPage - 2 && <span className="px-2 py-1">...</span>}

                {lastPage > 1 && (
                  <button
                    onClick={() => handlePageChange(lastPage)}
                    disabled={currentPage === lastPage}
                    className={`px-3 py-1 rounded-md min-w-[40px] ${
                      currentPage === lastPage 
                        ? 'bg-blue-600 text-white' 
                        : 'bg-white text-gray-700 hover:bg-gray-100'
                    }`}
                  >
                    {lastPage}
                  </button>
                )}
              </>
            )}

            <button
              onClick={() => handlePageChange(currentPage + 1)}
              disabled={currentPage === lastPage}
              className="px-3 py-1 rounded-md bg-white text-gray-700 enabled:hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Next &raquo;
            </button>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}