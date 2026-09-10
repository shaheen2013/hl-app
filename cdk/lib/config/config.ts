import { Account } from "../../../../hotelinking_gateway/fargate/config/types";

const region = "eu-west-1";

export const environments = {
	dev: {
		env: {
			account: Account.dev,
			region,
			apiGateway: "s4e89eysz1",
			autoscale: {
				albService: {
					cpu: 1024,
					memoryLimitMiB: 2048,
					targetValue: 70,
				},
			},
			subnets: {
				cidr1_public: "172.10.31.0/24",
				cidr2_public: "172.10.32.0/24",
			},
			kmsKeys: ["25bc7fca-8e33-4408-b8e0-c411ee8d6874"],
		},
	},
	prod: {
		env: {
			account: Account.prod,
			region,
			apiGateway: "o013mn5aff",
			autoscale: {
				albService: {
					cpu: 2048,
					memoryLimitMiB: 4096,
					targetValue: 70,
				},
			},
			subnets: {
				cidr1_public: "172.10.31.0/24",
				cidr2_public: "172.10.32.0/24",
			},
			kmsKeys: ["4f97ce7c-143b-4e5b-a773-5489741f0edf"],
		},
	},
};
