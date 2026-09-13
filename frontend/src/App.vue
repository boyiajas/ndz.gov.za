<template>
  <AppNavbar v-if="!isPortalRoute" />
  <router-view v-slot="{ Component }">
    <transition name="fade" mode="out-in">
      <component :is="Component" />
    </transition>
  </router-view>
  <AppFooter v-if="!isPortalRoute" />
</template>

<script>
import { computed, watch, nextTick } from 'vue'
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

    return {
      isPortalRoute: computed(() => Boolean(route.meta.portal)),
    }
  }
}
</script>
