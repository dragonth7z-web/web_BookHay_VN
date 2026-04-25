<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Repositories\CouponRepository;
use App\Services\SecondHandMarketService;

class PageController extends Controller
{
    public function __construct(
        protected CouponRepository $couponRepo,
        protected SecondHandMarketService $marketService
    ) {}

    /**
     * Active coupons listing page.
     * Redirects to account coupons if user is logged in.
     */
    public function coupons()
    {
        if (session('user_id')) {
            return redirect()->route('account.coupons');
        }

        $coupons = $this->couponRepo->getActiveCoupons();
        return view('pages.coupons', compact('coupons'));
    }

    /**
     * Trang FAQ - Hỏi đáp thường gặp.
     */
    public function faq()
    {
        // Nhóm FAQ theo nhóm/danh mục nếu có, sinon lấy tất cả
        $faqs = Faq::orderBy('sort_order')->get()->groupBy('group');
        return view('pages.faq', compact('faqs'));
    }

    /**
     * Trang Giới thiệu.
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Trang Liên hệ.
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Xử lý form liên hệ.
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string|min:20',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'message.min' => 'Nội dung liên hệ tối thiểu 20 ký tự.',
        ]);

        // Tạm thời lưu vào log hoặc gửi mail (chưa integrate email)
        \Illuminate\Support\Facades\Log::info('Contact Form', $request->only(['name', 'email', 'subject', 'message']));

        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong vòng 24-48 giờ.');
    }

    /**
     * Trang Chính sách vận chuyển.
     */
    public function shipping()
    {
        return view('pages.shipping');
    }

    /**
     * Trang Chính sách đổi trả.
     */
    public function return()
    {
        return view('pages.return');
    }

    /**
     * Trang Chính sách bảo mật.
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Trang Điều khoản sử dụng.
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Trang Theo dõi đơn hàng.
     */
    public function orderTracking()
    {
        return view('pages.order-tracking');
    }

    /**
     * Trang Cửa hàng.
     */
    public function stores()
    {
        return view('pages.stores');
    }

    /**
     * Second-hand book marketplace page.
     */
    public function secondHandMarket()
    {
        $featuredBooks      = $this->marketService->getFeaturedBooks();
        $marketStats        = $this->marketService->getMarketStats();
        $filterCategories   = $this->marketService->getFilterCategories();

        return view('pages.second-hand-market', compact(
            'featuredBooks',
            'marketStats',
            'filterCategories'
        ));
    }
}
