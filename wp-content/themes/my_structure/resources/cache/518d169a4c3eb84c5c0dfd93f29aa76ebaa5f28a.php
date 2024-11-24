<?php
$options = \Models\Options\OpzioniGlobaliFields::get();
?>
<header x-data="{ open: false }" class="p-6 bg-white lg:pb-0">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- lg+ -->
        <nav class="flex items-center justify-between h-16 lg:h-20">
            <div class="text-center flex flex-col items-center justify-between">
                <a href="#" title="" class="flex">
                    <img class="w-auto h-12 lg:h-12" src="<?php echo e($options->logo['url']); ?>" alt="" />
                </a>
                <div class="text-custom-dark-green font-bold text-xs">Project Africa Conservation</div>
            </div>

            <button @click="open = !open" type="button" class="inline-flex p-2 text-black transition-all duration-200 rounded-md lg:hidden focus:bg-gray-100 hover:bg-gray-100">
                <!-- Menu open: "hidden", Menu closed: "block" -->
                <svg x-show="!open" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>

                <!-- Menu open: "block", Menu closed: "hidden" -->
                <svg x-show="open" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="hidden lg:flex lg:items-center lg:ml-auto lg:space-x-10">
                <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item->url); ?>" title="" class="text-base font-medium text-black transition-all duration-200 hover:text-custom-green focus:text-custom-green font-nunitoSans"> <?php echo e($item->title); ?> </a>
                    <?php if(!empty($item->children)): ?>
                        <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subkey => $subitem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($subitem->url); ?>" title="" class="text-base font-medium text-black transition-all duration-200 hover:text-custom-green focus:text-custom-green font-nunitoSans"> <?php echo e($subitem->title); ?> </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <a href="#" title="" class="items-center justify-center hidden px-4 py-3 ml-10 text-base font-semibold text-white transition-all duration-200 bg-custom-green border border-transparent rounded-md lg:inline-flex hover:bg-custom-green focus:bg-custom-green" role="button">Dona ora</a>
        </nav>

        <!-- xs to lg -->
        <nav x-show="open" @click.away="open = false"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute inset-x-0 pt-4 pb-6 bg-white border border-gray-200 rounded-md shadow-md lg:hidden z-50">
            <div class="flow-root">
                <div class="flex flex-col items-center px-6 -my-2 space-y-4 pt-2">
                    <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <a href="<?php echo e($item->url); ?>"
                               title=""
                               class="text-base text-center font-medium text-black transition-all duration-200 hover:text-custom-green focus:text-custom-green font-nunitoSans flex items-center">
                                <?php echo e($item->title); ?>

                            </a>
                        </div>
                        <?php if(!empty($item->children)): ?>
                            <div class="mt-1 space-y-1">
                                <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subitem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($subitem->url); ?>"
                                       title=""
                                       class="block text-center text-sm font-medium text-gray-700 transition-all duration-200 hover:text-custom-green">
                                        <?php echo e($subitem->title); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="px-6 mt-6 text-center">
                <a href="#" title="" class="inline-flex justify-center px-4 py-3 text-base font-semibold text-white transition-all duration-200 bg-custom-green border border-transparent rounded-md tems-center hover:bg-custom-green focus:bg-custom-green" role="button">Dona ora</a>
            </div>
        </nav>
    </div>
</header>
<?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/partials/header-menu.blade.php ENDPATH**/ ?>