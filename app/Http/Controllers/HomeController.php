<?php

namespace App\Http\Controllers;

use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        $products = $this->products();
        $stats    = $this->stats();
        $values   = $this->values();
        $gallery  = $this->gallery();
        $faqs     = $this->faqs();
        $articles = Article::published()->latest('published_at')->take(3)->get();

        return view('home', compact('products', 'stats', 'values', 'gallery', 'faqs', 'articles'));
    }

    private function products(): array
    {
        return [
            'cengkeh' => [
                'name'      => 'Cengkeh (Cloves)',
                'latin'     => 'Syzygium aromaticum',
                'badge'     => 'Premium Cloves',
                'tag'       => 'Spice · Export Grade',
                'image'     => 'images/products/product-cengkeh.jpg',
                'imgLabel'  => "cengkeh\nclose-up photo",
                'shortDesc' => 'Hand-picked premium cloves from Maluku  rich in aroma, high in eugenol content.',
                'desc'      => "Hand-picked premium cloves from the Maluku archipelago  Indonesia's legendary Spice Islands. Rich in eugenol, our cengkeh is prized for its intense aroma and is exported to pharmaceutical, food, and fragrance industries worldwide.",
                'specs'     => [
                    ['Moisture Content',     '≤ 12%'],
                    ['Eugenol Content',      '≥ 72%'],
                    ['Admixture / Impurity', '≤ 1%'],
                    ['Broken / Damaged',     '≤ 3%'],
                    ['Grade',                'Grade A / B / C'],
                    ['Color',                'Dark reddish-brown'],
                    ['Packaging',            '50 kg Jute / PP Bags'],
                    ['Min. Order (MOQ)',      '1 Metric Ton'],
                    ['Shelf Life',           '24 months (dry storage)'],
                    ['Certificate',          'COA, COO, Phytosanitary'],
                ],
                'origins' => ['Maluku Utara', 'Ternate', 'Tidore', 'Sulawesi'],
            ],
            'kapulaga' => [
                'name'      => 'Kapulaga (Cardanom)',
                'latin'     => 'Elettaria cardamomum',
                'badge'     => 'Green Cardamom',
                'tag'       => 'Spice · Export Grade',
                'image'     => 'images/products/product-kapulaga.png',
                'imgLabel'  => "kapulaga\npods photo",
                'shortDesc' => 'Green cardamom pods from highland farms  intensely fragrant and export-grade.',
                'desc'      => 'Freshly harvested green cardamom pods from the highland farms of Java and Sumatra. Intensely fragrant with a warm, spicy-sweet profile  a premium choice for food manufacturers and spice traders in Europe and Asia.',
                'specs'     => [
                    ['Moisture Content',     '≤ 10%'],
                    ['Volatile Oil',         '≥ 4%'],
                    ['Admixture / Impurity', '≤ 0.5%'],
                    ['Broken Pods',          '≤ 5%'],
                    ['Grade',                'FAQ / Bold / Extra Bold'],
                    ['Color',                'Light to medium green'],
                    ['Packaging',            '25 kg / 50 kg Jute Bags'],
                    ['Min. Order (MOQ)',      '500 kg'],
                    ['Shelf Life',           '18 months'],
                    ['Certificate',          'COA, COO, Phytosanitary'],
                ],
                'origins' => ['Jawa Barat', 'Sumatera Utara', 'Lampung'],
            ],
            'lada' => [
                'name'      => 'Lada (Pepper)',
                'latin'     => 'Piper nigrum',
                'badge'     => 'Black & White Pepper',
                'tag'       => 'Spice · Export Grade',
                'image'     => 'images/products/product-lada.png',
                'imgLabel'  => "lada\npeppercorns photo",
                'shortDesc' => 'Black & white pepper from Lampung  bold, peppery profile for the global spice trade.',
                'desc'      => "Premium black and white pepper from Lampung and Bangka Belitung  Indonesia's top pepper-producing regions. Our lada offers a bold, sharp piperine profile favoured by spice traders and food processors across Europe and East Asia.",
                'specs'     => [
                    ['Moisture Content',  '≤ 12% (Black) / ≤ 14% (White)'],
                    ['Piperine Content',  '≥ 4.5%'],
                    ['Admixture',         '≤ 1%'],
                    ['Light Berries',     '≤ 2%'],
                    ['Grade',             'ASTA 570 / 550 / FAQ'],
                    ['Type',              'Black Pepper / White Pepper'],
                    ['Packaging',         '50 kg PP / Jute Bags'],
                    ['Min. Order (MOQ)',   '1 Metric Ton'],
                    ['Shelf Life',        '24 months'],
                    ['Certificate',       'COA, COO, BPOM, Phytosanitary'],
                ],
                'origins' => ['Lampung', 'Bangka Belitung', 'Kalimantan Barat'],
            ],
            'kopi' => [
                'name'      => 'Kopi (Coffee)',
                'latin'     => 'Coffea arabica / Coffea canephora',
                'badge'     => 'Single-Origin Coffee',
                'tag'       => 'Coffee · Export Grade',
                'image'     => 'images/products/product-kopi.jpg',
                'imgLabel'  => "kopi\nbeans photo",
                'shortDesc' => 'Single-origin Arabica & Robusta from Aceh, Toraja, and Flores highlands.',
                'desc'      => "Single-origin Arabica and Robusta beans sourced from Indonesia's most celebrated highlands  Gayo (Aceh), Toraja (Sulawesi), and Flores. Each lot is carefully processed, sorted, and graded to specialty and commercial export standards.",
                'specs'     => [
                    ['Moisture Content', '≤ 12.5%'],
                    ['Defect Value',     '≤ 11 (Grade 1 Specialty)'],
                    ['Screen Size',      '15/16 (Arabica) / 14/15 (Robusta)'],
                    ['Process',          'Washed / Natural / Honey'],
                    ['Altitude',         '1,000 – 1,700 masl'],
                    ['Type',             'Arabica & Robusta'],
                    ['Packaging',        '60 kg GrainPro / Jute Bags'],
                    ['Min. Order (MOQ)', '300 kg'],
                    ['Shelf Life',       '12 months (green bean)'],
                    ['Certificate',      'COA, COO, Rainforest / Organic on request'],
                ],
                'origins' => ['Gayo – Aceh', 'Toraja – Sulawesi', 'Flores', 'Mandheling – Sumatra'],
            ],
            'kakao' => [
                'name'      => 'Kakao (Cocoa)',
                'latin'     => 'Theobroma cacao',
                'badge'     => 'Fine Flavour Cocoa',
                'tag'       => 'Cocoa · Export Grade',
                'image'     => 'images/products/product-kakao.jpg',
                'imgLabel'  => "kakao\nbeans photo",
                'shortDesc' => 'Fine-flavour cocoa beans from Sulawesi  fermented and dried to international standards.',
                'desc'      => 'Fine-flavour cocoa beans from the fertile volcanic soils of Sulawesi and Papua. Properly fermented and sun-dried to meet international buyer specifications  ideal for premium chocolate makers and confectionery manufacturers in Europe and Asia.',
                'specs'     => [
                    ['Moisture Content',    '≤ 7.5%'],
                    ['Fermentation Index',  '≥ 75% (fully fermented)'],
                    ['Bean Count',          '≤ 100 beans / 100g'],
                    ['Moldy / Slaty Beans', '≤ 3% / ≤ 3%'],
                    ['Grade',               'Grade A / Superior'],
                    ['Type',                'Bulk Forastero / Fine Flavour'],
                    ['Packaging',           '60 kg Jute / GrainPro Bags'],
                    ['Min. Order (MOQ)',     '1 Metric Ton'],
                    ['Shelf Life',          '24 months'],
                    ['Certificate',         'COA, COO, Phytosanitary, UTZ on request'],
                ],
                'origins' => ['Sulawesi Tengah', 'Sulawesi Tenggara', 'Papua', 'Maluku'],
            ],
        ];
    }

    private function stats(): array
    {
        return [
            ['value' => '50+', 'label' => 'Happy Clients'],
            ['value' => '12+', 'label' => 'Years Active'],
            ['value' => '5',   'label' => 'Commodities'],
            ['value' => '20+', 'label' => 'Countries Reached'],
        ];
    }

    private function values(): array
    {
        return [
            ['icon' => '✦', 'title' => 'Premium Quality',    'text' => 'Every shipment meets strict international standards  laboratory-tested, certified, and traceable to origin farms.'],
            ['icon' => '◈', 'title' => 'Sustainable Sourcing','text' => 'We partner with local farmers using eco-friendly practices and fair-trade principles, supporting community livelihoods.'],
            ['icon' => '◎', 'title' => 'Reliable Logistics',  'text' => 'From inland warehouse to port to your door  our logistics team ensures on-time, damage-free delivery every time.'],
        ];
    }

    private function gallery(): array
    {
        return [
            ['image' => 'images/gallery/gallery-cengkeh.jpg', 'label' => "Cengkeh\nharvest scene", 'caption' => 'Cengkeh Harvest'],
            ['image' => 'images/gallery/gallery-kapulaga.png',  'label' => "Kapulaga\nfarm",          'caption' => 'Kapulaga Farm'],
            ['image' => 'images/gallery/gallery-kopi.png',                                  'label' => "Kopi\nbeans",             'caption' => 'Kopi Beans'],
            ['image' => 'images/gallery/gallery-kakao.png',   'label' => "Kakao\npods",             'caption' => 'Kakao Pods'],
            ['image' => null,                                  'label' => "Export\nwarehouse",       'caption' => 'Our Warehouse'],
        ];
    }

    private function faqs(): array
    {
        return [
            [
                'question' => 'What is the minimum order quantity (MOQ)?',
                'answer'   => 'Our MOQ varies by commodity. For most products, the minimum is 1 metric ton per shipment. Contact us to discuss specific requirements for your order.',
            ],
            [
                'question' => 'Do you provide quality and phytosanitary certificates?',
                'answer'   => 'Yes. All our shipments are accompanied by a Certificate of Origin, Phytosanitary Certificate, and quality analysis reports from accredited Indonesian laboratories.',
            ],
            [
                'question' => 'Which countries do you currently ship to?',
                'answer'   => 'We currently export to Europe (Netherlands, Germany, UK, Spain) and Asia (Japan, South Korea, China, India). We are open to new markets  contact us to explore options.',
            ],
            [
                'question' => 'Is it possible to receive a complimentary sample?',
                'answer'   => 'Yes, a free sample is available. Shipping costs are the responsibility of the recipient.',
            ],
            [
                'question' => 'Can we visit your facilities or request product samples?',
                'answer'   => 'Absolutely. We welcome facility visits and are happy to send product samples before you place a bulk order. Reach out via our contact form to arrange this.',
            ],
        ];
    }

    private function posts(): array
    {
        return [
            [
                'image'   => 'images/blog/featured.jpg',
                'date'    => 'May 2025',
                'title'   => "Indonesia's Spice Export Growth: What Buyers Need to Know in 2025",
                'excerpt' => 'A deep dive into rising global demand for Indonesian cengkeh and kapulaga, and how Nusagrade is positioned to meet it.',
            ],
            [
                'image'   => 'images/blog/2.jpg',
                'date'    => 'Apr 2025',
                'title'   => 'Kapulaga Market Outlook: Opportunity for European Buyers',
                'excerpt' => 'Why cardamom from Indonesia is gaining ground against Guatemalan supply chains.',
            ],
            [
                'image'   => 'images/blog/3.jpg',
                'date'    => 'Mar 2025',
                'title'   => 'Fair Trade Coffee: How Nusagrade Supports Indonesian Farmers',
                'excerpt' => 'Our commitment to ethical sourcing and what it means for the communities we work with.',
            ],
        ];
    }
}
