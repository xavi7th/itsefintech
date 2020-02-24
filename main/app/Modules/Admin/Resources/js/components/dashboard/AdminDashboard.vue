<template>
  <main>
    <page-header pageTitle="Dashboard"></page-header>
    <div class="content">
      <div class="row">
        <div class="col-12">
          <div class="card pb-0">
            <div class="card-title">
              <h4>SALES LIST</h4>
            </div>
            <div class="card-body pt-0 pb-0">
              <div class="card-row">
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th class="w-10p">ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                        <th class="w-10p">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="record in statistics.sales_rep_sales" :key="record.id">
                        <td>{{ record.id }}</td>
                        <td>{{ record.customer }}</td>
                        <td>{{ record.phone }}</td>
                        <td>{{ record.payment_method }}</td>
                        <td>{{ record.request_date }}</td>
                        <td class="text-center">
                          <span
                            class="badge badge-dot bodge-lg"
                            :class="{'badge-warning': (record.is_paid && !record.is_payment_confirmed), 'badge-danger': !record.is_paid, 'badge-success': record.is_payment_confirmed, }"
                          ></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-8 col-12">
          <div class="card">
            <div class="card-body p-0">
              <div class="pt-30">
                <h4 class="pl-30 mb-20">Orders Summary</h4>
                <div id="purchase-chart" class="h-150"></div>
                <div class="flex j-c-between px-30 py-45">
                  <div>
                    <span class="fs-16 fw-600 d-block">15</span>
                    <small class="fs-14 text-light">Unpaid</small>
                  </div>
                  <div>
                    <span class="fs-16 fw-600 d-block">3</span>
                    <small class="fs-14 text-light">Unconfirmed</small>
                  </div>
                  <div>
                    <span class="fs-16 fw-600 d-block">1</span>
                    <small class="fs-14 text-light">Completed</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xl-4 col-12">
          <div class="card">
            <div class="card-title">
              <h4>NOTIFICATIONS</h4>
            </div>
            <div class="card-body py-0">
              <div class="card-row">
                <div class="widget-item" v-for="act in statistics.recent_activities" :key="act.id">
                  <div class="thumbnail-md bg-info mr-20">
                    <i class="fas fa-info"></i>
                  </div>
                  <div class="flex j-c-between w-100p">
                    <span class="widget-title">{{ act.activity }}</span>
                    <span class="widget-text-small">{{ act.created_at }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
  import {
    getSalesRepStatistics,
    requestCardStock
  } from "@admin-assets/js/config";
  export default {
    name: "AdminDashboard",
    data: () => ({
      statistics: {}
    }),
    mounted() {
      this.$emit("page-loaded");
      console.log(this.$user);

      this.initialiseChart();
    },
    methods: {
      initialiseChart() {
        axios.get(getSalesRepStatistics).then(({ data: statistics }) => {
          this.statistics = statistics;

          new Morris.Line({
            element: "purchase-chart",
            data: [...this.statistics.monthly_summary],
            xkey: "day",
            ykeys: ["num_of_sales"],
            labels: ["Num of Sales"],
            lineColors: ["#f90a48"],
            lineWidth: 2,
            grid: false,
            axes: false,
            gridTextSize: 10,
            padding: 5,
            hideHover: "auto",
            resize: true
          });
        });
      }
    }
  };
</script>

<style lang="scss" scoped>
  .card-gradient-dark {
    small {
      font-weight: bold;
    }
    background-image: linear-gradient(to right, #2196f3 30%, #03a9f4 100%);
    .d-block {
      color: #fff;
    }
  }

  .card-gradient {
    small {
      font-weight: bold;
    }
    &.purple {
      background-image: linear-gradient(to right, #3b2169 30%, #673ab7 100%);
    }
    &.green {
      background-image: linear-gradient(to right, #009688 30%, #4caf50 100%);
    }
  }

  .card-request {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    text-align: center;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
  }
</style>
