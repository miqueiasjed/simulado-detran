<div>
    @if($banners->count() > 0)
    <div class="banner-carrossel relative w-full overflow-hidden bg-gray-100" 
         x-data="bannerCarrossel({{ $banners->count() }})" 
         x-init="startAutoSlide()">
        
        <!-- Container das imagens -->
        <div class="banner-slides flex transition-transform duration-500 ease-in-out"
             :style="'transform: translateX(-' + (currentSlide * 100) + '%)'">
            
            @foreach($banners as $index => $banner)
            <div class="banner-slide w-full flex-shrink-0 relative">
                @if($banner->imagem_desktop)
                    <!-- Banner com Imagem -->
                    <picture>
                        <!-- Mobile -->
                        @if($banner->imagem_mobile)
                        <source media="(max-width: 640px)" srcset="{{ $banner->getImagemUrl('mobile') }}">
                        @endif
                        
                        <!-- Tablet -->
                        @if($banner->imagem_tablet)
                        <source media="(max-width: 1024px)" srcset="{{ $banner->getImagemUrl('tablet') }}">
                        @endif
                        
                        <!-- Desktop -->
                        <img src="{{ $banner->getImagemUrl('desktop') }}" 
                             alt="{{ $banner->titulo }}"
                             class="w-full h-auto object-cover h-32 sm:h-40 md:h-48 lg:h-64 xl:h-80">
                    </picture>
                @else
                    <!-- Banner apenas com texto (quando não há imagem) -->
                    <div class="w-full bg-gradient-to-r from-gov-blue to-gov-darkblue text-white p-8 text-center h-32 sm:h-40 md:h-48 lg:h-64 xl:h-80 flex items-center justify-center">
                        <div>
                            <h3 class="text-lg md:text-2xl lg:text-3xl font-bold mb-2">{{ $banner->titulo }}</h3>
                            @if($banner->descricao)
                            <p class="text-sm md:text-base lg:text-lg opacity-90">{{ $banner->descricao }}</p>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Link overlay (se houver link) -->
                @if($banner->link)
                <a href="{{ $banner->link }}" 
                   target="_blank" 
                   class="absolute inset-0 z-10 cursor-pointer"
                   aria-label="{{ $banner->titulo }}">
                </a>
                @endif
                
                <!-- Overlay com título e descrição (opcional) -->
                @if($banner->titulo || $banner->descricao)
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-white">
                    @if($banner->titulo)
                    <h3 class="text-lg font-semibold mb-1">{{ $banner->titulo }}</h3>
                    @endif
                    @if($banner->descricao)
                    <p class="text-sm opacity-90">{{ $banner->descricao }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <!-- Indicadores (bolinhas) -->
        @if($banners->count() > 1)
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
            @foreach($banners as $index => $banner)
            <button @click="goToSlide({{ $index }})"
                    class="w-2 h-2 rounded-full transition-all duration-300"
                    :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'">
            </button>
            @endforeach
        </div>
        
        {{-- Setas de navegação (removidas por solicitação do usuário) --}}
        {{-- 
        <button @click="prevSlide()" 
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full transition-all duration-300 z-20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button @click="nextSlide()" 
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white p-2 rounded-full transition-all duration-300 z-20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        --}}
        @endif
    </div>

    <script>
    function bannerCarrossel(totalSlides) {
        return {
            currentSlide: 0,
            totalSlides: totalSlides,
            autoSlideInterval: null,
            
            startAutoSlide() {
                if (this.totalSlides <= 1) return;
                
                this.autoSlideInterval = setInterval(() => {
                    this.nextSlide();
                }, 7000); // 7 segundos
            },
            
            stopAutoSlide() {
                if (this.autoSlideInterval) {
                    clearInterval(this.autoSlideInterval);
                }
            },
            
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
            },
            
            prevSlide() {
                this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
            },
            
            goToSlide(index) {
                this.currentSlide = index;
                // Reinicia o timer quando o usuário interage
                this.stopAutoSlide();
                setTimeout(() => this.startAutoSlide(), 1000);
            }
        }
    }
    </script>
    @endif
</div>
