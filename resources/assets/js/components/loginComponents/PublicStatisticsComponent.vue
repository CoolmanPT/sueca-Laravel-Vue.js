<template>
	<div>
		<div class="card">
			<div class="card-header bg-dark">
				<h2 class="text-center text-light">Statistics</h2>
			</div>
			<div class="card-body">
				<table class="table">
					<thead>
						<tr>
							<th>Registred Players</th>
							<th>Active Games</th>
							<th>Total Games</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{{stats.registedUsers}}</td>
							<td>{{stats.activeGames}}</td>
							<td>{{stats.totalGames}}</td>
						</tr>
					</tbody>
				</table>
				<button @click="showHide" v-if="moreShowed === 0" class="btn btn-dark float-right mt-1">Show More Statistics</button>
				<button @click="showHide" v-if="moreShowed === 1" class="btn btn-dark float-right mt-1 mb-2">Hide More Statistics</button>
				<div v-if="moreShowed === 1">
					<table class="table table-striped">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2" class="text-center">Most Games Played</th>
							</tr>
							<tr>
								<th>Nickname</th>
								<th class="text-right">Number of Games Played</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="user in stats.top5MostGames" v-bind:key="user.id">
								<td>{{user.nickname}}</td>
								<td class="text-right">{{user.total_games_played}}</td>
							</tr>
						</tbody>
					</table>

					<table class="table table-striped">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2" class="text-center">Most Points</th>
							</tr>
							<tr>
								<th>Nickname</th>
								<th class="text-right">Total Points</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="user in stats.top5MostPoints" v-bind:key="user.id">
								<td>{{user.nickname}}</td>
								<td class="text-right">{{user.total_points}}</td>
							</tr>
						</tbody>
					</table>

					<table class="table table-striped">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2" class="text-center">Best Average Score</th>
							</tr>
							<tr>
								<th>Nickname</th>
								<th class="text-right">Average Score</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="user in stats.top5BestAvg" v-bind:key="user.id">
								<td>{{user.nickname}}</td>
								<td class="text-right">{{(user.total_points/(user.total_games_played)==0 ? 1 : user.total_games_played)}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data: function(){
		return {
			stats: [],
			moreShowed: 0
		}
	},
	methods: {
		getStats(){
			axios.get("api/statistics", this.$root.headers).then(response => {
				this.stats = response.data.statistics;
				console.log(response.data);
			}).catch(response => {
				console.log("Error on getting stats");
			});
		},
		showHide(){
			this.moreShowed === 0 ? this.moreShowed = 1 : this.moreShowed = 0;
		},
	},
	mounted(){
		this.getStats();
		this.moreShowed = 0;
	}
}
</script>
