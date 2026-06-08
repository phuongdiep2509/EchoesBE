@extends('admin.layouts.app')
@section('title', 'Quản lý hạng vé')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold"><i class="fas fa-ticket-alt me-2 text-primary"></i>Quản lý hạng vé</h4>
        <small class="text-muted">Tổng: {{ count($tickets) }} hạng vé</small>
    </div>
    <div>
        <a href="{{ route('admin.hang-ve.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Thêm mới
        </a>
    </div>
</div>

{{-- Bộ lọc --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.hang-ve.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-1">Lọc theo sự kiện (Concert)</label>
                <select name="concert_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Tất cả sự kiện --</option>
                    @foreach($concerts as $concert)
                        <option value="{{ $concert->MaSuKien }}" {{ $concert_id == $concert->MaSuKien ? 'selected' : '' }}>
                            {{ $concert->TenSuKien }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if($concert_id)
            <div class="col-md-2">
                <a href="{{ route('admin.hang-ve.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i>Xóa lọc
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Hiển thị thông báo --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead style="background:#1a1a2e; color:#fff;">
                <tr>
                    <th class="ps-3">Sự kiện</th>
                    <th>Tên hạng vé</th>
                    <th>Khu vực</th>
                    <th>Giá vé</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">Đã bán</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td class="ps-3 small">
                        <strong>{{ $ticket->khuVuc->concert->TenSuKien ?? 'N/A' }}</strong>
                    </td>
                    <td><span class="badge bg-primary">{{ $ticket->TenHangVe }}</span></td>
                    <td>{{ $ticket->khuVuc->TenKhuVuc ?? 'N/A' }}</td>
                    <td class="text-danger fw-bold">{{ number_format($ticket->GiaVe, 0, ',', '.') }} đ</td>
                    <td class="text-center">{{ $ticket->SoLuongMoBan }}</td>
                    <td class="text-center">{{ $ticket->SoLuongDaBan }}</td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-sm p-0 border-0 bg-transparent text-decoration-none dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if($ticket->TrangThaiHienTai === 'SapMoBan')
                                    <span class="badge bg-info text-dark"><i class="fas fa-calendar-plus me-1"></i>Sắp mở bán</span>
                                @elseif($ticket->TrangThaiHienTai === 'DangMoBan')
                                    <span class="badge bg-success"><i class="fas fa-ticket-alt me-1"></i>Đang mở bán</span>
                                @elseif($ticket->TrangThaiHienTai === 'HetVe')
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Hết vé</span>
                                @elseif($ticket->TrangThaiHienTai === 'DaKetThuc')
                                    <span class="badge bg-secondary"><i class="fas fa-calendar-check me-1"></i>Đã kết thúc</span>
                                @elseif($ticket->TrangThaiHienTai === 'TamDung')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-pause-circle me-1"></i>Tạm dừng</span>
                                @elseif($ticket->TrangThaiHienTai === 'DaHuy')
                                    <span class="badge bg-dark"><i class="fas fa-ban me-1"></i>Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">{{ $ticket->TrangThaiHienTai }}</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu shadow-sm" style="font-size: 0.875rem;">
                                <li>
                                    <button class="dropdown-item btn-change-status" data-url="{{ route('admin.hang-ve.updateStatus', $ticket->MaHangVe) }}" data-status="DangMoBan" data-status-name="Bật Auto / Mở bán"><i class="fas fa-play-circle me-2 text-success"></i>Bật Auto / Mở bán</button>
                                </li>
                                <li>
                                    <button class="dropdown-item btn-change-status" data-url="{{ route('admin.hang-ve.updateStatus', $ticket->MaHangVe) }}" data-status="TamDung" data-status-name="Tạm dừng"><i class="fas fa-pause-circle me-2 text-warning"></i>Tạm dừng</button>
                                </li>
                                <li>
                                    <button class="dropdown-item btn-change-status text-danger" data-url="{{ route('admin.hang-ve.updateStatus', $ticket->MaHangVe) }}" data-status="DaHuy" data-status-name="Đã hủy"><i class="fas fa-ban me-2"></i>Đã hủy</button>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.hang-ve.show', $ticket->MaHangVe) }}"
                               class="btn btn-outline-info" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.hang-ve.edit', $ticket->MaHangVe) }}"
                               class="btn btn-outline-warning" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-ticket-alt fa-2x mb-2 d-block"></i>
                        Không tìm thấy hạng vé nào.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-change-status').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                const status = this.getAttribute('data-status');
                const statusName = this.getAttribute('data-status-name');
                
                Swal.fire({
                    title: 'Xác nhận thay đổi?',
                    text: `Bạn có chắc chắn muốn đổi trạng thái hạng vé sang "${statusName}" không?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Lưu',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ TrangThai: status })
                        })
                        .then(response => response.json().then(data => ({ status: response.status, body: data })))
                        .then(res => {
                            if (res.status === 200 && res.body.success) {
                                Swal.fire('Thành công!', res.body.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                let errorMsg = res.body.message || 'Thay đổi trạng thái thất bại.';
                                if (res.body.errors && res.body.errors.TrangThai) {
                                    errorMsg = res.body.errors.TrangThai[0];
                                }
                                Swal.fire('Thất bại!', errorMsg, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Lỗi!', 'Thay đổi trạng thái thất bại. Không thể kết nối tới máy chủ.', 'error');
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
