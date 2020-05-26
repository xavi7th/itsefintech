<template>
  <main>
    <page-header pageTitle="View Activities"></page-header>
    <div class="content">
      <div class="card">
        <div class="card-body">
          <table class="table table-bordered table-hover" id="datatable1">
            <thead>
              <tr>
                <th>ID</th>
                <th>Activity</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="activity in activities" :key="activity.id">
                <td>{{ activity.id }}</td>
                <td>{{ activity.activity }}</td>
                <td>{{ activity.time }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
  import { adminViewActivities } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ViewActivities",
    data: () => ({
      activities: [],
      sectionLoading: false
    }),
    components: {
      PreLoader
    },
    created() {
      this.getActivities();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      getActivities() {
        BlockToast.fire({
          text: "loading activities..."
        });
        axios.get(adminViewActivities).then(({ data: { activities } }) => {
          this.activities = activities;

          if (this.$isDesktop) {
            this.$nextTick(() => {
              $(function() {
                $("#datatable1").DataTable({
                  responsive: true,
                  scrollX: false,
                  order: [[0, "desc"]],
                  language: {
                    searchPlaceholder: "Search...",
                    sSearch: ""
                  }
                });
              });
            });
          } else {
            this.$nextTick(() => {
              $(function() {
                $("#datatable1").DataTable({
                  responsive: false,
                  scrollX: true,
                  order: [[0, "desc"]],
                  language: {
                    searchPlaceholder: "Search...",
                    sSearch: ""
                  }
                });
              });
            });
          }

          swal.close();
        });
      }
    }
  };
</script>

<style lang="scss" scoped>
  .modal-right,
  .modal-left {
    .modal-dialog {
      min-width: 35%;

      .modal-body {
        overflow-y: auto;
      }
    }
  }
  .section-loader {
    min-height: 90vh;
    margin-left: 0;
    position: fixed;
  }

  .form-group {
    position: relative;

    span {
      position: absolute;
      bottom: 7px;
      right: 20px;
      color: #d33;
      opacity: 0;
      transition: ease-in 300ms opacity;
      pointer-events: none;
    }

    &.has-error {
      .form-control {
        border-color: #f90a48;
        box-shadow: 0 0 10px rgba(249, 10, 72, 0.2);
      }

      > span {
        opacity: 1;
      }
    }
  }
</style>
