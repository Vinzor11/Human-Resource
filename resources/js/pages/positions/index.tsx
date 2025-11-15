import { CustomModalForm } from '@/components/custom-modal-form'
import { CustomTable } from '@/components/custom-table'
import { CustomToast, toast } from '@/components/custom-toast'
import { PositionModalFormConfig } from '@/config/forms/position-modal-form'
import { PositionTableConfig } from '@/config/tables/position-table'
import AppLayout from '@/layouts/app-layout'
import { type BreadcrumbItem } from '@/types'
import { Head, router, useForm, usePage } from '@inertiajs/react'
import { useState } from 'react'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Manage Positions', href: '/positions' },
]

interface Position {
  id: number
  pos_code: string
  pos_name: string
  description?: string
}

interface Pagination<T> {
  data: T[]
  links: any[]
  from: number
  to: number
  total: number
}

interface FlashProps {
  flash?: {
    success?: string
    error?: string
  }
}

interface IndexProps {
  positions: Pagination<Position>
}

export default function PositionIndex({ positions }: IndexProps) {
  const { flash } = usePage<FlashProps>().props
  const [modalOpen, setModalOpen] = useState(false)
  const [mode, setMode] = useState<'create' | 'view' | 'edit'>('create')
  const [selectedItem, setSelectedItem] = useState<Position | null>(null)

  const { data, setData, errors, processing, reset, post } = useForm({
    pos_code: '',
    pos_name: '',
    description: '',
    _method: 'POST',
  })

  const closeModal = () => {
    setMode('create')
    setSelectedItem(null)
    reset()
    setModalOpen(false)
  }

  const handleModalToggle = (open: boolean) => {
    setModalOpen(open)
    if (!open) closeModal()
  }

  const openModal = (mode: 'create' | 'view' | 'edit', item?: Position) => {
    setMode(mode)
    if (item) {
      setSelectedItem(item)
      setData({
        pos_code: item.pos_code,
        pos_name: item.pos_name,
        description: item.description || '',
        _method: 'PUT',
      })
    } else {
      reset()
    }
    setModalOpen(true)
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    const method = mode === 'edit' ? 'PUT' : 'POST'
    data._method = method

    post(
      mode === 'edit' && selectedItem
        ? route('positions.update', selectedItem.id)
        : route('positions.store'),
      {
        onSuccess: (res: { props: FlashProps }) => {
          const message = res?.props?.flash?.success
          if (message) toast.success(message)
          closeModal()
        },
        onError: (err) => {
          const message = err?.message
          if (message) toast.error(message)
        },
      }
    )
  }
  
  const handleDelete = (routeStr: string) => {
    if (confirm('Are you sure you want to delete?')) {
      router.delete(routeStr, {
        preserveScroll: true,
        onSuccess: (res: { props: FlashProps }) => {
          const message = res?.props?.flash?.success
          if (message) toast.success(message)
          closeModal()
        },
        onError: (err) => {
          const message = err?.message
          if (message) toast.error(message)
        },
      })
    }
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Positions" />
      <CustomToast />

      <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <CustomModalForm
          addButton={PositionModalFormConfig.addButton}
          title={
            mode === 'view'
              ? 'View Position'
              : mode === 'edit'
              ? 'Update Position'
              : PositionModalFormConfig.title
          }
          description={PositionModalFormConfig.description}
          fields={PositionModalFormConfig.fields}
          buttons={PositionModalFormConfig.buttons}
          data={data}
          setData={setData}
          errors={errors}
          processing={processing}
          handleSubmit={handleSubmit}
          open={modalOpen}
          onOpenChange={handleModalToggle}
          mode={mode}
        />

       <CustomTable
                 columns={PositionTableConfig.columns}
                 actions={PositionTableConfig.actions}
                 data={positions.data}
                 from={positions.from}
                 onDelete={handleDelete}
                 onView={(item) => openModal('view', item)}
                 onEdit={(item) => openModal('edit', item)}
                 isModal={true}
                 getRowKey={(row) => row.position_id} // ✅ use this in CustomTable to assign `key`
               />
             </div>
    </AppLayout>
  )
}
