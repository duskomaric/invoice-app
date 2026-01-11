<div 
    x-data="{
        toasts: [],
        toastsHovered: false,
        expanded: false,
        layout: 'default',
        position: 'top-center',
        paddingBetweenToasts: 15,
        deleteToastWithId (id){
            for(let i = 0; i < this.toasts.length; i++){
                if(this.toasts[i].id === id){
                    this.toasts.splice(i, 1);
                    break;
                }
            }
        },
        burnToast(id){
            burnToast = this.getToastWithId(id);
            if(burnToast){
                burnToast.show = false;
                setTimeout(() => { this.deleteToastWithId(id); }, 300);
            }
        },
        getToastWithId(id){
            for(let i = 0; i < this.toasts.length; i++){
                if(this.toasts[i].id === id){
                    return this.toasts[i];
                }
            }
        },
        stackToasts(){
            this.positionToasts();
            this.calculateHeightOfToastsContainer();
            setTimeout(() => { this.calculateHeightOfToastsContainer(); }, 300);
        },
        positionToasts(){
            if(this.toasts.length == 0) return;
            let topOrBottomPos = 0;
            for(let i = this.toasts.length-1; i >= 0; i--){
                let toast = document.getElementById(this.toasts[i].id);
                if(toast){
                    let newPosition = topOrBottomPos;
                    if(this.expanded){
                        if(this.position.includes('bottom')){
                            toast.style.top = 'auto';
                            toast.style.bottom = newPosition + 'px';
                        } else {
                            toast.style.top = newPosition + 'px';
                            toast.style.bottom = 'auto';
                        }
                    } else {
                        toast.style.top = newPosition + 'px';
                        toast.style.bottom = 'auto';
                    }
                    topOrBottomPos = topOrBottomPos + toast.offsetHeight + this.paddingBetweenToasts;
                }
            }
        },
        calculateHeightOfToastsContainer(){
            if(this.toasts.length == 0) {
                this.$refs.toastContainer.style.height = '0px';
                return;
            }
            let lastToast = this.toasts[this.toasts.length - 1];
            let lastToastRectangle = document.getElementById(lastToast.id)?.getBoundingClientRect();
            let firstToast = this.toasts[0];
            let firstToastRectangle = document.getElementById(firstToast.id)?.getBoundingClientRect();
            if(lastToastRectangle && firstToastRectangle){
                if(this.toastsHovered){
                    if(this.position.includes('bottom')){
                        this.$refs.toastContainer.style.height = ((firstToastRectangle.top + firstToastRectangle.height) - lastToastRectangle.top) + 'px';
                    } else {
                        this.$refs.toastContainer.style.height = ((lastToastRectangle.top + lastToastRectangle.height) - firstToastRectangle.top) + 'px';
                    }
                } else {
                    this.$refs.toastContainer.style.height = firstToastRectangle.height + 'px';
                }
            }
        }
    }"
    @set-toasts-layout.window="
        layout = $event.detail.layout;
        if(layout == 'expanded'){
            expanded = true;
        } else {
            expanded = false;
        }
        stackToasts();
    "
    @toast-show.window="
        let toastObj = { 
            id: 'toast-' + Math.random().toString(16).slice(2), 
            show: false, 
            message: $event.detail.message,
            description: $event.detail.description,
            type: $event.detail.type,
            html: $event.detail.html,
            position: $event.detail.position
        };
        toasts.push(toastObj);
        position = toastObj.position;
        setTimeout(() => { toastObj.show = true; stackToasts(); }, 5);
        setTimeout(() => { burnToast(toastObj.id); }, 4000);
    "
    @mouseenter="toastsHovered = true; expanded = true; stackToasts();"
    @mouseleave="toastsHovered = false; expanded = false; stackToasts();"
    x-init="stackToasts();"
    x-ref="toastContainer"
    class="fixed block w-full group pointer-events-none z-[99]"
    :class="{ 'right-0 top-0 px-6 sm:px-8 flex justify-end' : position=='top-right', 'left-0 top-0 px-6 sm:px-8' : position=='top-left', 'left-1/2 -translate-x-1/2 top-0' : position=='top-center', 'right-0 bottom-0 px-6 sm:px-8 flex justify-end' : position=='bottom-right', 'left-0 bottom-0 px-6 sm:px-8' : position=='bottom-left', 'left-1/2 -translate-x-1/2 bottom-0' : position=='bottom-center' }">
    <template x-for="(toast, index) in toasts" :key="toast.id">
        <div
            :id="toast.id"
            x-data="{ toastHovered: false }"
            x-show="toast.show"
            @mouseenter="toastHovered=true"
            @mouseleave="toastHovered=false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute w-full duration-300 ease-out sm:max-w-sm sm:w-full mt-2 sm:mt-3 pointer-events-auto"
            :class="{ 'opacity-100' : toastsHovered, 'opacity-100' : !toastsHovered && index == toasts.length - 1, 'opacity-0' : !toastsHovered && index != toasts.length - 1 }">
            <div 
                x-show="!toast.html"
                class="flex w-full flex-col items-center space-y-4 sm:items-end"
                :class="{ 'sm:items-start' : position == 'top-left' || position == 'bottom-left', 'sm:items-end' : position == 'top-right' || position == 'bottom-right', 'sm:items-center' : position == 'top-center' || position == 'bottom-center' }">
                <div class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <template x-if="toast.type == 'success'">
                                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </template>
                                <template x-if="toast.type == 'info'">
                                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </template>
                                <template x-if="toast.type == 'warning'">
                                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </template>
                                <template x-if="toast.type == 'danger'">
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </template>
                                <template x-if="toast.type == 'default'">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </template>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p x-text="toast.message" class="text-sm font-medium text-gray-900"></p>
                                <p x-show="toast.description" x-text="toast.description" class="mt-1 text-sm text-gray-500"></p>
                            </div>
                            <div class="ml-4 flex flex-shrink-0">
                                <button @click="burnToast(toast.id)" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="toast.html" x-html="toast.html" class="w-full max-w-sm bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"></div>
        </div>
    </template>
</div>

<script>
window.toast = function(message, options = {}){
    let description = '';
    let type = 'default';
    let position = 'top-center';
    let html = '';
    if(typeof options.description != 'undefined') description = options.description;
    if(typeof options.type != 'undefined') type = options.type;
    if(typeof options.position != 'undefined') position = options.position;
    if(typeof options.html != 'undefined') html = options.html;
    
    window.dispatchEvent(new CustomEvent('toast-show', { detail : { type: type, message: message, description: description, position : position, html: html }}));
}
</script>
