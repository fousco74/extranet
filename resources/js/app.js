import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import vClickOutside from 'v-click-outside'
import '@vueform/multiselect/themes/default.css'




createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Link)
      .use(Head)
      .use(ZiggyVue)
      .use(vClickOutside)
      .mount(el)
  }
})
