import "./assets/main.css";
import { createApp, h, provide } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import { DefaultApolloClient } from "@vue/apollo-composable";
import { ApolloClient, InMemoryCache, from } from "@apollo/client/core";
import { createUploadLink } from "apollo-upload-client";
import { setContext } from "@apollo/client/link/context";
import { onError } from "@apollo/client/link/error";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";
import Swal from "sweetalert2";
import VueApexCharts from "vue3-apexcharts";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { dom } from "@fortawesome/fontawesome-svg-core";
import { fab } from "@fortawesome/free-brands-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";


library.add(fas);
library.add(fab);
library.add(far);
dom.watch();


const uploadLink = createUploadLink({
  uri: "http://localhost:8000/graphql",
  credentials: "include"
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
          text: "You are not logged in.",
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
  link: from([errorLink, authLink, uploadLink]),
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
// app.use(VueApexCharts);
app.component("VueApexCharts", VueApexCharts);
app.component("font-awesome-icon", FontAwesomeIcon);
app.mount("#app");
