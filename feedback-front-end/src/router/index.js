import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import HomeView from "../views/HomeView.vue";
import login from "@/views/login.vue";
import questions from "../views/questions/index.vue";
import addQuestion from "@/views/questions/add.vue";
import answers from "../views/answers/index.vue";
import orders from "../views/orders/index.vue";
import addOrder from "../views/orders/add.vue";
import feedback from "@/views/answers/feedback.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView
    },
    {
      path: "/login",
      name: "login",
      component: login,
      meta: { guest: true }
    },
    {
      path: "/questions",
      name: "questions",
      component: questions,
      meta: { requiresAuth: true },
    },
    {
      path: "/add-question",
      name: "add-question",
      component: addQuestion,
      meta: { requiresAuth: true }
    },
    {
      path: "/answers",
      name: "answers",
      component: answers,
      meta: { requiresAuth: true }
    },
    {
      path: "/orders",
      name: "orders",
      component: orders,
      meta: { requiresAuth: true }
    },
    {
      path: "/add-order",
      name: "add-order",
      component: addOrder,
      meta: { requiresAuth: true }
    },
    {
      path: "/feedback",
      name: "feedback",
      component: feedback
    }
  ],
});

router.beforeEach((to, from, next) => {
  const loggedIn = useAuthStore();

  if (to.meta.requiresAuth && !loggedIn.isLoggedIn) {
    next({ name: "login" });
  } else if (to.meta.guest && loggedIn.isLoggedIn) {
    next({ name: "home" });
  } else {
    next();
  }
});

export default router;
