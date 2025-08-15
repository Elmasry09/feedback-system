import { defineStore } from "pinia";
import axios from "axios";
import apiClient from "@/axios/api";
import Swal from "sweetalert2";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    authUser: null,
    isAuthenticated: false,
  }),

  persist: true,

  getters: {
    user: (state) => state.authUser,
    isLoggedIn: (state) => state.isAuthenticated,
  },

  actions: {
    async login(user) {
      try {
        const response = await axios.post(
          "http://127.0.0.1:8000/api/login",
          user
        );
        this.authUser = response.data.data.user;
        this.isAuthenticated = true;
        localStorage.setItem("jwt_token", response.data.data.token);
        return response.status;
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops.sssss..",
          text: error.response.data.message,
        });
      }
    },

    async logout() {
      const token = localStorage.getItem("jwt_token");
      try {
        const response = await apiClient.post("/logout", null, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.authUser = null;
        this.isAuthenticated = false;
        localStorage.removeItem("jwt_token");
        return response.status;
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops.sssss..",
          text: error.response.data.message,
        });
      }
    },
    async update(user) {
      const token = localStorage.getItem("jwt_token");
      try {
        const response = await apiClient.post("/update", user, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.authUser = response.data.user;
        return response.status;
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops..",
          text: error.response.data.message,
        });
      }
    },
  },
});
