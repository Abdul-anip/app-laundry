<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('landing_page_settings', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('hero_title')->default('Laundry Mudah, Hidup Lebih Santai');
            $table->text('hero_subtitle')->default('Pesan online, kami jemput & cuci, antar kembali bersih. Simple!');
            $table->string('hero_cta_primary')->default('Pesan Sekarang');
            $table->string('hero_cta_secondary')->default('Lacak Pesanan');
            
            // How It Works Section
            $table->string('how_it_works_title')->default('Cara Kerja');
            $table->text('how_it_works_subtitle')->default('Tiga langkah mudah untuk cucian bersih dan wangi');
            
            // Services Section
            $table->string('services_title')->default('Layanan Kami');
            $table->text('services_subtitle')->default('Pilih paket yang sesuai dengan kebutuhanmu');
            
            // Why Choose Us Section
            $table->string('why_choose_title')->default('Kenapa Pilih Kami?');
            $table->text('why_choose_subtitle')->default('Komitmen kami untuk memberikan layanan terbaik');
            
            // CTA Section
            $table->string('cta_section_title')->default('Yuk, Coba Sekarang!');
            $table->text('cta_section_text')->default('Rasakan pengalaman laundry yang mudah dan menyenangkan. Order pertamamu menanti!');
            $table->string('cta_button_text')->default('Mulai Order →');
            
            // Footer
            $table->text('footer_description')->default('Layanan laundry online terpercaya dengan pickup & delivery untuk kemudahan hidupmu.');
            $table->string('contact_email')->default('hello@laundryku.com');
            $table->string('contact_phone')->default('+62 812-3456-7890');
            $table->string('contact_address')->default('Jakarta, Indonesia');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_settings');
    }
};
