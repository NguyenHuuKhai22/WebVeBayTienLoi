@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
 <!-- Dashboard Cards -->
 <div class="dashboard-cards">
        <div class="card">
            <div class="stat-card">
                <div class="stat-icon users"><i class="fas fa-users"></i></div>
                <div class="stat-details">
                    <p class="stat-value">1,200</p>
                    <p class="stat-label">Người dùng</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="stat-card">
                <div class="stat-icon orders"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-details">
                    <p class="stat-value">320</p>
                    <p class="stat-label">Đơn hàng</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="stat-card">
                <div class="stat-icon products"><i class="fas fa-box"></i></div>
                <div class="stat-details">
                    <p class="stat-value">150</p>
                    <p class="stat-label">Sản phẩm</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="stat-card">
                <div class="stat-icon revenue"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-details">
                    <p class="stat-value">$24,500</p>
                    <p class="stat-label">Doanh thu</p>
                </div>
            </div>
        </div>
    </div>
@endsection
