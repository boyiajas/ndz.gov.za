<template>
  <AppNavbar />
  <router-view v-slot="{ Component }">
    <transition name="fade" mode="out-in">
      <component :is="Component" />
    </transition>
  </router-view>
  <AppFooter />
</template>

<script>
import { watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import AppNavbar from './components/AppNavbar.vue'
import AppFooter from './components/AppFooter.vue'

export default {
  name: 'App',
  components: { AppNavbar, AppFooter },
  setup() {
    const route = useRoute()
    watch(
      () => route.path,
      () => {
        nextTick(() => {
          window.scrollTo({ top: 0, behavior: 'instant' })
        })
      }
    )
  }
}
</script>
