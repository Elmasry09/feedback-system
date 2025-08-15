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
import profile from "@/views/profile.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
      meta: { title: "Home" },
    },
    {
      path: "/login",
      name: "login",
      component: login,
      meta: { guest: true, title: "Login" },
    },
    {
      path: "/profile",
      name: "profile",
      component: profile,
      meta: { requiresAuth: true, title: "Profile" },
    },
    {
      path: "/questions",
      name: "questions",
      component: questions,
      meta: { requiresAuth: true, title: "Questions" },
    },
    {
      path: "/add-question",
      name: "add-question",
      component: addQuestion,
      meta: { requiresAuth: true, title: "Add Question" },
    },
    {
      path: "/answers",
      name: "answers",
      component: answers,
      meta: { requiresAuth: true, title: "Answers" },
    },
    {
      path: "/orders",
      name: "orders",
      component: orders,
      meta: { requiresAuth: true, title: "Orders" },
    },
    {
      path: "/add-order",
      name: "add-order",
      component: addOrder,
      meta: { requiresAuth: true, title: "Add Order" },
    },
    {
      path: "/feedback",
      name: "feedback",
      component: feedback,
      meta: { title: "Feedback" },
    },
  ],
});

router.beforeEach((to, from, next) => {
  const loggedIn = useAuthStore();

  // Change browser tab title
  document.title = to.meta.title || "Feedback";

  if (to.meta.requiresAuth && !loggedIn.isLoggedIn) {
    next({ name: "login" });
  } else if (to.meta.guest && loggedIn.isLoggedIn) {
    next({ name: "home" });
  } else {
    next();
  }
});

export default router;
