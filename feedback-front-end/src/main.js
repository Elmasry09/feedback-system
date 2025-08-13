import "./assets/main.css";
import { createApp, h, provide } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import { DefaultApolloClient } from "@vue/apollo-composable";
import {
  ApolloClient,
  createHttpLink,
  InMemoryCache,
  from,
} from "@apollo/client/core";
import { setContext } from "@apollo/client/link/context";
import { onError } from "@apollo/client/link/error";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import Swal from "sweetalert2";

const httpLink = createHttpLink({
  uri: "http://localhost:8000/graphql",
  credentials: "include",
});

const authLink = setContext((_, { headers }) => {
  const token = localStorage.getItem("jwt_token");
  return {
    headers: {
      ...headers,
      Authorization: token ? `Bearer ${token}` : "",
    },
  };
});

const errorLink = onError(({ graphQLErrors, networkError }) => {
  if (graphQLErrors) {
    for (const err of graphQLErrors) {
      if (err.message === "Unauthenticated.") {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "You are not logged in."
        });
        localStorage.clear();
        router.push("/login");
      }
    }
  }

  if (networkError) {
    console.error("[Network error]:", networkError);
  }
});

const cache = new InMemoryCache();

const apolloClient = new ApolloClient({
  link: from([errorLink, authLink, httpLink]), 
  cache,
});

const app = createApp({
  setup() {
    provide(DefaultApolloClient, apolloClient);
  },
  render: () => h(App),
});

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

app.use(pinia);
app.use(router);
app.mount("#app");
